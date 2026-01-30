#!/bin/bash
# Check for GraphQL-related errors in Nginx logs

echo "=== Checking for GraphQL/500 Errors ==="
echo ""

# Check for 500 errors
echo "1. Recent 500 errors:"
sudo grep "500\|Internal Server Error" /var/log/nginx/error.log | tail -20
echo ""

# Check for PHP-FPM errors
echo "2. Recent PHP-FPM errors:"
sudo grep -i "php\|fpm\|fastcgi" /var/log/nginx/error.log | tail -20
echo ""

# Check for GraphQL-specific errors
echo "3. GraphQL-related errors:"
sudo grep -i "graphql" /var/log/nginx/error.log | tail -20
echo ""

# Check access log for GraphQL requests
echo "4. Recent GraphQL requests (access log):"
sudo grep "/graphql" /var/log/nginx/access.log | tail -10
echo ""

# Check for any errors in the last hour
echo "5. All errors in last hour:"
sudo grep "$(date -d '1 hour ago' '+%Y/%m/%d %H')" /var/log/nginx/error.log | tail -30
echo ""

# Check PHP-FPM log
echo "6. PHP-FPM error log:"
sudo tail -30 /var/log/php8.1-fpm.log 2>/dev/null || echo "PHP-FPM log not found or empty"
echo ""
