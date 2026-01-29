#!/bin/bash

# Script to append GraphQL fix code to plugin file via SSH
# The file is already uploaded to /tmp/mishap-creative-works-new.php

# SSH command to append the code section
ssh shadrach@backend.shadrach-tuck.dev << 'EOF'

# Backup original file first
cp /var/www/html/portfolio-backend/app/public/wp-content/plugins/mishap-creative-works/mishap-creative-works.php \
   /var/www/html/portfolio-backend/app/public/wp-content/plugins/mishap-creative-works/mishap-creative-works.php.backup

# Extract just the new code (lines after 614)
tail -n +615 /tmp/mishap-creative-works-new.php > /tmp/new_code.txt

# Append to original file
cat /tmp/new_code.txt >> /var/www/html/portfolio-backend/app/public/wp-content/plugins/mishap-creative-works/mishap-creative-works.php

echo "✅ Code appended to plugin file"
echo "Backup saved to: mishap-creative-works.php.backup"

EOF

echo "✅ Plugin file updated!"
