#!/bin/bash
# Script to check Nginx configuration and diagnose CORS/500 errors

echo "=== Checking Nginx Configuration ==="
echo ""

# Test Nginx config syntax
echo "1. Testing Nginx configuration syntax..."
sudo nginx -t
echo ""

# Show the location /graphql block if it exists
echo "2. Checking for location /graphql block..."
if sudo grep -q "location /graphql" /etc/nginx/sites-enabled/backend.shadrach-tuck.dev; then
    echo "✓ location /graphql block found"
    echo ""
    echo "Current configuration:"
    sudo grep -A 30 "location /graphql" /etc/nginx/sites-enabled/backend.shadrach-tuck.dev
else
    echo "✗ location /graphql block NOT found"
fi
echo ""

# Check server block structure
echo "3. Server block structure:"
sudo grep -n "server {" /etc/nginx/sites-enabled/backend.shadrach-tuck.dev
echo ""

# Check recent Nginx errors
echo "4. Recent Nginx errors (last 20 lines):"
sudo tail -20 /var/log/nginx/error.log
echo ""

# Check PHP-FPM status
echo "5. PHP-FPM status:"
sudo systemctl status php8.1-fpm --no-pager -l
echo ""

# Test GraphQL endpoint
echo "6. Testing GraphQL endpoint with curl:"
curl -H "Origin: https://shadrach-tuck.dev" \
     -H "Content-Type: application/json" \
     -X POST \
     -d '{"query":"{ __typename }"}' \
     https://backend.shadrach-tuck.dev/graphql \
     -v 2>&1 | head -30
echo ""

# Check if plugin file exists
echo "7. Checking plugin file:"
if [ -f "/var/www/html/portfolio-backend/app/public/wp-content/plugins/mishap-creative-works/mishap-creative-works.php" ]; then
    echo "✓ Plugin file exists"
    ls -lh /var/www/html/portfolio-backend/app/public/wp-content/plugins/mishap-creative-works/mishap-creative-works.php
    echo ""
    echo "CORS handler function exists:"
    grep -n "mishap_early_cors_handler" /var/www/html/portfolio-backend/app/public/wp-content/plugins/mishap-creative-works/mishap-creative-works.php | head -5
else
    echo "✗ Plugin file NOT found"
fi
echo ""

# Check debug log directory
echo "8. Checking debug log directory:"
if [ -d "/var/www/html/portfolio-backend/.cursor" ]; then
    echo "✓ Debug log directory exists"
    if [ -f "/var/www/html/portfolio-backend/.cursor/debug.log" ]; then
        echo "✓ Debug log file exists"
        echo "Last 10 lines:"
        tail -10 /var/www/html/portfolio-backend/.cursor/debug.log
    else
        echo "✗ Debug log file NOT found (will be created on first request)"
    fi
else
    echo "✗ Debug log directory NOT found"
    echo "Creating directory..."
    sudo mkdir -p /var/www/html/portfolio-backend/.cursor
    sudo chown www-data:www-data /var/www/html/portfolio-backend/.cursor
    sudo chmod 755 /var/www/html/portfolio-backend/.cursor
    echo "✓ Directory created"
fi
