#!/bin/bash
# Script to find database credentials for wordpress_db

echo "=== Finding Database Credentials ==="
echo ""

echo "1. Checking database users that might have access to wordpress_db:"
sudo mysql -u root -p <<EOF
SELECT User, Host FROM mysql.user WHERE User != '';
SHOW GRANTS FOR 'root'@'localhost';
SELECT User, Host FROM mysql.db WHERE Db = 'wordpress_db';
EOF

echo ""
echo "2. Checking if there's a WordPress-specific user:"
sudo mysql -u root -p -e "SELECT User, Host FROM mysql.user WHERE User LIKE '%wordpress%' OR User LIKE '%wp%';"

echo ""
echo "3. Testing connection with root user:"
echo "   (This will help determine if root can access wordpress_db)"
sudo mysql -u root -p -e "USE wordpress_db; SHOW TABLES;" 2>&1 | head -10

echo ""
echo "4. If root works, you can use:"
echo "   DB_NAME=wordpress_db"
echo "   DB_USER=root"
echo "   DB_PASSWORD=(the root MySQL password you just entered)"
echo ""
echo "   OR create a dedicated WordPress user (recommended):"
echo ""
echo "   Run this SQL to create a WordPress user:"
echo "   CREATE USER 'wp_user'@'localhost' IDENTIFIED BY 'strong_password_here';"
echo "   GRANT ALL PRIVILEGES ON wordpress_db.* TO 'wp_user'@'localhost';"
echo "   FLUSH PRIVILEGES;"
