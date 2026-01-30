#!/bin/bash
# Script to create .env.production file on the server

echo "=== Creating .env.production file ==="
echo ""

# Get the directory
ENV_FILE="/var/www/html/portfolio-backend/.env.production"

# Check if file already exists
if [ -f "$ENV_FILE" ]; then
    echo "⚠️  File already exists: $ENV_FILE"
    echo "Backing up to ${ENV_FILE}.backup"
    sudo cp "$ENV_FILE" "${ENV_FILE}.backup"
fi

echo "Creating .env.production file..."
echo ""
echo "You'll need to provide:"
echo "  - Database name"
echo "  - Database user"
echo "  - Database password"
echo "  - Database host (usually 'localhost')"
echo ""

# Prompt for database credentials
read -p "Database name: " DB_NAME
read -p "Database user: " DB_USER
read -sp "Database password: " DB_PASSWORD
echo ""
read -p "Database host [localhost]: " DB_HOST
DB_HOST=${DB_HOST:-localhost}

# Generate WordPress security keys
echo ""
echo "Generating WordPress security keys..."
KEYS=$(curl -s https://api.wordpress.org/secret-key/1.1/salt/)

# Extract keys from the response
AUTH_KEY=$(echo "$KEYS" | grep "AUTH_KEY" | cut -d"'" -f4)
SECURE_AUTH_KEY=$(echo "$KEYS" | grep "SECURE_AUTH_KEY" | cut -d"'" -f4)
LOGGED_IN_KEY=$(echo "$KEYS" | grep "LOGGED_IN_KEY" | cut -d"'" -f4)
NONCE_KEY=$(echo "$KEYS" | grep "NONCE_KEY" | cut -d"'" -f4)
AUTH_SALT=$(echo "$KEYS" | grep "AUTH_SALT" | cut -d"'" -f4)
SECURE_AUTH_SALT=$(echo "$KEYS" | grep "SECURE_AUTH_SALT" | cut -d"'" -f4)
LOGGED_IN_SALT=$(echo "$KEYS" | grep "LOGGED_IN_SALT" | cut -d"'" -f4)
NONCE_SALT=$(echo "$KEYS" | grep "NONCE_SALT" | cut -d"'" -f4)

# Create the file
sudo tee "$ENV_FILE" > /dev/null <<EOF
# WordPress Production Environment Configuration
# Generated on $(date)

WP_ENV=production
WP_ENVIRONMENT_TYPE=production

# Database settings
DB_NAME=$DB_NAME
DB_USER=$DB_USER
DB_PASSWORD=$DB_PASSWORD
DB_HOST=$DB_HOST
DB_CHARSET=utf8mb4
DB_COLLATE=

# WordPress URLs
WP_HOME=https://backend.shadrach-tuck.dev
WP_SITEURL=https://backend.shadrach-tuck.dev

# Authentication keys and salts
AUTH_KEY='$AUTH_KEY'
SECURE_AUTH_KEY='$SECURE_AUTH_KEY'
LOGGED_IN_KEY='$LOGGED_IN_KEY'
NONCE_KEY='$NONCE_KEY'
AUTH_SALT='$AUTH_SALT'
SECURE_AUTH_SALT='$SECURE_AUTH_SALT'
LOGGED_IN_SALT='$LOGGED_IN_SALT'
NONCE_SALT='$NONCE_SALT'
WP_CACHE_KEY_SALT='dkE2ai1ly!JF[=#ugj#avUncG&u1XWlG4*]Gkbiv>cb,JYhjFYUq*Dg]<EPwi/VA'

# Debug settings (disabled in production)
WP_DEBUG=false
WP_DEBUG_LOG=false
WP_DEBUG_DISPLAY=false
SCRIPT_DEBUG=false

# Performance settings
WP_MEMORY_LIMIT=256M
WP_MAX_MEMORY_LIMIT=512M

# Security settings
FS_METHOD=direct
DISALLOW_FILE_EDIT=true
FORCE_SSL_ADMIN=true
EOF

# Set proper permissions
sudo chown www-data:www-data "$ENV_FILE"
sudo chmod 600 "$ENV_FILE"

echo ""
echo "✅ Created $ENV_FILE"
echo ""
echo "File permissions set to:"
ls -la "$ENV_FILE"
echo ""
echo "⚠️  IMPORTANT: Verify the database credentials are correct!"
echo "   Test connection: mysql -u $DB_USER -p$DB_PASSWORD $DB_NAME -e 'SELECT 1;'"
echo ""
