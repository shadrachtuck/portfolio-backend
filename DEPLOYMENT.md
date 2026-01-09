# WordPress Backend Deployment Guide

This guide covers deploying your headless WordPress backend with WPGraphQL to various hosting platforms.

## Prerequisites

Before deploying, ensure you have:
- A backup of your database (use the `app/sql/local.sql` or export via phpMyAdmin)
- Your WordPress admin credentials
- List of all installed plugins (WPGraphQL, ACF, WPGraphQL-ACF, FluentForm, etc.)

## Deployment Options

### Option 1: Traditional WordPress Hosting (Recommended for Simplicity)

**Best for:** Quick deployment, managed updates, less technical maintenance

**Recommended Providers:**
- **WP Engine** - Premium, excellent performance, includes CDN
- **Kinsta** - Great for headless WordPress, uses Google Cloud
- **SiteGround** - Affordable, good performance
- **Flywheel** - Good for developers, similar to Local

**Steps:**
1. Sign up for a hosting account
2. Create a new WordPress site
3. Upload your files or use their migration tool
4. Import your database
5. Update `wp-config.php` with production database credentials
6. Update WordPress site URL and home URL in database
7. Configure domain name
8. Test GraphQL endpoint at `https://your-domain.com/graphql`

### Option 2: VPS/Cloud Hosting (DigitalOcean, AWS, etc.)

**Best for:** Full control, cost-effective, scalable

#### DigitalOcean Droplet

**Steps:**

1. **Create a Droplet:**
   ```bash
   # Choose Ubuntu 22.04 LTS
   # Recommended: 2GB RAM minimum (4GB+ preferred)
   # Add SSH keys
   ```

2. **Initial Server Setup:**
   ```bash
   # SSH into your droplet
   ssh root@your-droplet-ip
   
   # Update system
   sudo apt update && sudo apt upgrade -y
   ```

3. **Install LEMP Stack (Nginx, MySQL, PHP):**
   ```bash
   # Install Nginx
   sudo apt install nginx -y
   
   # Install MySQL
   sudo apt install mysql-server -y
   sudo mysql_secure_installation
   
   # Install PHP 8.1+ with extensions
   sudo apt install php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-gd -y
   
   # Start services
   sudo systemctl start nginx
   sudo systemctl start mysql
   sudo systemctl enable nginx
   sudo systemctl enable mysql
   ```

4. **Create Database:**
   ```bash
   sudo mysql -u root -p
   ```
   ```sql
   CREATE DATABASE wordpress_db;
   CREATE USER 'wp_user'@'localhost' IDENTIFIED BY 'strong_password_here';
   GRANT ALL PRIVILEGES ON wordpress_db.* TO 'wp_user'@'localhost';
   FLUSH PRIVILEGES;
   EXIT;
   ```

5. **Upload WordPress Files:**
   ```bash
   # Option A: Using SCP
   scp -r /path/to/portfolio-backend/app/public/* root@your-droplet-ip:/var/www/html/
   
   # Option B: Using Git
   cd /var/www/html
   git clone https://github.com/shadrachtuck/portfolio-backend.git
   cp -r portfolio-backend/app/public/* .
   ```

6. **Set Permissions:**
   ```bash
   sudo chown -R www-data:www-data /var/www/html
   sudo chmod -R 755 /var/www/html
   sudo chmod -R 775 /var/www/html/wp-content
   ```

7. **Configure Nginx:**
   Create `/etc/nginx/sites-available/wordpress`:
   ```nginx
   server {
       listen 80;
       server_name your-domain.com www.your-domain.com;
       root /var/www/html;
       index index.php index.html;

       location / {
           try_files $uri $uri/ /index.php?$args;
       }

       location ~ \.php$ {
           include snippets/fastcgi-php.conf;
           fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
           fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
           include fastcgi_params;
       }

       location ~ /\.ht {
           deny all;
       }
   }
   ```
   ```bash
   sudo ln -s /etc/nginx/sites-available/wordpress /etc/nginx/sites-enabled/
   sudo nginx -t
   sudo systemctl reload nginx
   ```

8. **Import Database:**
   ```bash
   mysql -u wp_user -p wordpress_db < /path/to/local.sql
   ```

9. **Update wp-config.php:**
   ```php
   define('DB_NAME', 'wordpress_db');
   define('DB_USER', 'wp_user');
   define('DB_PASSWORD', 'your_password');
   define('DB_HOST', 'localhost');
   define('WP_HOME', 'https://your-domain.com');
   define('WP_SITEURL', 'https://your-domain.com');
   ```

10. **Install SSL Certificate (Let's Encrypt):**
    ```bash
    sudo apt install certbot python3-certbot-nginx -y
    sudo certbot --nginx -d your-domain.com -d www.your-domain.com
    ```

### Option 3: AWS Lightsail (Managed WordPress)

**Steps:**

1. Go to AWS Lightsail Console
2. Create WordPress instance
3. Choose instance plan (minimum $5/month)
4. Configure domain name
5. SSH into instance and update files/database
6. Install SSL via Lightsail console

### Option 4: Google Cloud Platform

Similar to AWS, use Compute Engine or Google Cloud WordPress solutions.

## Post-Deployment Checklist

After deploying, ensure:

1. **GraphQL Endpoint Works:**
   - Visit: `https://your-domain.com/graphql`
   - Should see GraphQL playground or schema endpoint

2. **Update Frontend Environment Variable:**
   - In your Vercel project, update `VITE_WP_GRAPHQL_URL` to `https://your-domain.com/graphql`

3. **Test WPGraphQL Queries:**
   ```graphql
   {
     webProjects {
       nodes {
         title
         slug
       }
     }
   }
   ```

4. **Configure CORS (if needed):**
   Add to `wp-config.php`:
   ```php
   define('GRAPHQL_DEBUG', false);
   // Allow CORS for your frontend domain
   header('Access-Control-Allow-Origin: https://your-frontend.vercel.app');
   header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
   header('Access-Control-Allow-Headers: Content-Type');
   ```

5. **Security Hardening:**
   - Update all plugins and WordPress core
   - Use strong passwords
   - Install security plugin (Wordfence, Sucuri)
   - Enable two-factor authentication
   - Limit login attempts

6. **Performance Optimization:**
   - Install caching plugin (WP Rocket, W3 Total Cache)
   - Enable Gzip compression
   - Use CDN (Cloudflare free tier)
   - Optimize images

## Database Migration

When migrating from local to production:

1. **Export Local Database:**
   - Use phpMyAdmin or command line: `mysqldump -u root -p local > backup.sql`
   - Or use the existing `app/sql/local.sql`

2. **Update URLs in Database:**
   After importing, run SQL:
   ```sql
   UPDATE wp_options SET option_value = 'https://your-domain.com' WHERE option_name = 'siteurl';
   UPDATE wp_options SET option_value = 'https://your-domain.com' WHERE option_name = 'home';
   ```

3. **Import to Production:**
   ```bash
   mysql -u wp_user -p wordpress_db < backup.sql
   ```

## Environment Variables for Production

Update `wp-config.php` with production settings:

```php
// Database
define('DB_NAME', 'production_db_name');
define('DB_USER', 'production_db_user');
define('DB_PASSWORD', 'production_db_password');
define('DB_HOST', 'localhost');

// URLs
define('WP_HOME', 'https://your-domain.com');
define('WP_SITEURL', 'https://your-domain.com');

// Security
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);

// Increase memory limit
define('WP_MEMORY_LIMIT', '256M');
```

## Troubleshooting

### GraphQL Endpoint Not Working

1. Check WPGraphQL plugin is activated
2. Visit `/wp-admin/admin.php?page=graphql-settings`
3. Verify GraphQL endpoint is enabled
4. Check permalinks are set (Settings > Permalinks > Save Changes)

### CORS Errors

Add CORS headers via plugin or `.htaccess`:
```apache
<IfModule mod_headers.c>
    Header set Access-Control-Allow-Origin "https://your-frontend.vercel.app"
    Header set Access-Control-Allow-Methods "POST, GET, OPTIONS"
    Header set Access-Control-Allow-Headers "Content-Type"
</IfModule>
```

### Database Connection Errors

1. Verify database credentials in `wp-config.php`
2. Check database user has proper permissions
3. Ensure MySQL service is running

## Recommended Hosting Comparison

| Provider | Price | Ease of Use | Performance | Best For |
|----------|-------|-------------|-------------|----------|
| WP Engine | $$$ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | Professional sites |
| Kinsta | $$$ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | Headless WordPress |
| DigitalOcean | $ | ⭐⭐⭐ | ⭐⭐⭐⭐ | Developers |
| AWS Lightsail | $ | ⭐⭐⭐ | ⭐⭐⭐⭐ | Scalability |
| SiteGround | $$ | ⭐⭐⭐⭐ | ⭐⭐⭐⭐ | Small-medium sites |

## Next Steps

1. Choose your hosting provider
2. Deploy WordPress backend
3. Update frontend `VITE_WP_GRAPHQL_URL` environment variable
4. Test GraphQL queries
5. Configure domain and SSL
6. Set up backups
7. Monitor performance
