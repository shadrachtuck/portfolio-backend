# WordPress Backend Deployment Checklist

Use this checklist when deploying your WordPress backend to production.

## Pre-Deployment

- [ ] Backup local database (`app/sql/local.sql` or export via phpMyAdmin)
- [ ] List all installed plugins and versions
- [ ] Test GraphQL endpoint locally
- [ ] Document all ACF field groups and custom post types
- [ ] Check WordPress and plugin versions are up to date
- [ ] Review `wp-config.php` for sensitive information

## Hosting Setup

- [ ] Choose hosting provider (see DEPLOYMENT.md)
- [ ] Create hosting account
- [ ] Purchase domain (if not already owned)
- [ ] Set up DNS records
- [ ] Create MySQL database and user
- [ ] Note database credentials (name, user, password, host)

## File Upload

- [ ] Upload WordPress files to server
- [ ] Set correct file permissions (755 for directories, 644 for files)
- [ ] Set `wp-content` directory to 775
- [ ] Upload custom plugin: `mishap-creative-works`

## Configuration

- [ ] Copy `wp-config.production.php` to `wp-config.php`
- [ ] Update database credentials in `wp-config.php`
- [ ] Generate new authentication keys/salts from https://api.wordpress.org/secret-key/1.1/salt/
- [ ] Update `WP_HOME` and `WP_SITEURL` with production domain
- [ ] Set `WP_DEBUG` to false
- [ ] Configure `WP_MEMORY_LIMIT` appropriately

## Database Migration

- [ ] Export local database
- [ ] Import database to production server
- [ ] Update URLs in database:
  ```sql
  UPDATE wp_options SET option_value = 'https://your-domain.com' WHERE option_name = 'siteurl';
  UPDATE wp_options SET option_value = 'https://your-domain.com' WHERE option_name = 'home';
  ```
- [ ] Search and replace old URLs (use WP-CLI or search-replace plugin):
  - `http://localhost:10004` → `https://your-domain.com`
- [ ] Test database connection

## WordPress Installation

- [ ] Run WordPress installation if fresh install
- [ ] Log in to WordPress admin
- [ ] Activate required plugins:
  - [ ] WPGraphQL
  - [ ] Advanced Custom Fields (ACF)
  - [ ] WPGraphQL for Advanced Custom Fields
  - [ ] FluentForm (if used)
  - [ ] Mishap Creative Works (custom plugin)

## Plugin Configuration

- [ ] Configure WPGraphQL:
  - [ ] Visit `/wp-admin/admin.php?page=graphql-settings`
  - [ ] Enable GraphQL endpoint
  - [ ] Note GraphQL endpoint URL: `https://your-domain.com/graphql`
  - [ ] Configure CORS if needed
- [ ] Verify ACF fields show in GraphQL
- [ ] Check custom post types are registered
- [ ] Test GraphQL queries

## Security

- [ ] Change default admin username
- [ ] Use strong passwords
- [ ] Install security plugin (Wordfence, Sucuri, or iThemes Security)
- [ ] Configure two-factor authentication
- [ ] Limit login attempts
- [ ] Enable firewall if available
- [ ] Set up regular backups
- [ ] Review and secure `.htaccess` file

## SSL/HTTPS

- [ ] Install SSL certificate (Let's Encrypt, Cloudflare, or provider SSL)
- [ ] Force HTTPS in `wp-config.php`:
  ```php
  define('FORCE_SSL_ADMIN', true);
  ```
- [ ] Update WordPress to use HTTPS URLs
- [ ] Test SSL certificate is working

## Performance

- [ ] Install caching plugin (WP Rocket, W3 Total Cache, or WP Super Cache)
- [ ] Configure CDN (Cloudflare free tier recommended)
- [ ] Enable Gzip compression
- [ ] Optimize images
- [ ] Configure browser caching

## Testing

- [ ] Test GraphQL endpoint: `https://your-domain.com/graphql`
- [ ] Test GraphQL queries:
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
- [ ] Test WordPress admin login
- [ ] Test custom post types are accessible
- [ ] Test ACF fields in GraphQL
- [ ] Test all frontend connections

## Frontend Integration

- [ ] Update frontend `VITE_WP_GRAPHQL_URL` environment variable in Vercel:
  - Old: `http://localhost:10004/graphql`
  - New: `https://your-domain.com/graphql`
- [ ] Redeploy frontend if needed
- [ ] Test frontend can fetch data from production backend
- [ ] Verify CORS is configured correctly

## CORS Configuration (if needed)

If your frontend can't access the GraphQL endpoint:

### Option 1: WPGraphQL Settings
- Go to WPGraphQL Settings in WordPress admin
- Configure CORS settings

### Option 2: Add to `.htaccess`
```apache
<IfModule mod_headers.c>
    Header set Access-Control-Allow-Origin "https://your-frontend.vercel.app"
    Header set Access-Control-Allow-Methods "POST, GET, OPTIONS"
    Header set Access-Control-Allow-Headers "Content-Type"
</IfModule>
```

### Option 3: Add to `wp-config.php`
```php
// Allow CORS for frontend
function add_cors_headers() {
    header('Access-Control-Allow-Origin: https://your-frontend.vercel.app');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
}
add_action('init', 'add_cors_headers');
```

## Post-Deployment

- [ ] Set up automated backups (daily recommended)
- [ ] Configure monitoring/uptime checks
- [ ] Document production credentials (store securely)
- [ ] Update documentation with production URLs
- [ ] Test all functionality end-to-end
- [ ] Monitor error logs for first few days

## Quick Reference

### Important URLs
- WordPress Admin: `https://your-domain.com/wp-admin`
- GraphQL Endpoint: `https://your-domain.com/graphql`
- GraphQL Playground: `https://your-domain.com/graphiql` (if enabled)

### Essential Commands
```bash
# Database backup
mysqldump -u user -p database_name > backup.sql

# Search and replace URLs in database
wp search-replace 'http://localhost:10004' 'https://your-domain.com' --all-tables

# Update URLs
wp option update home 'https://your-domain.com'
wp option update siteurl 'https://your-domain.com'
```

### Troubleshooting

**GraphQL not working:**
1. Check WPGraphQL plugin is activated
2. Save permalinks: Settings > Permalinks > Save Changes
3. Check `.htaccess` is writable

**CORS errors:**
- Configure CORS headers (see above)
- Check frontend environment variable is correct

**Database connection errors:**
- Verify credentials in `wp-config.php`
- Check database user has proper permissions
- Ensure MySQL service is running

**404 errors:**
- Check permalinks are set correctly
- Verify `.htaccess` exists and is configured
- Check server mod_rewrite is enabled

## Support

For deployment issues:
- Check DEPLOYMENT.md for detailed instructions
- Review hosting provider documentation
- Check WordPress Codex: https://wordpress.org/support/
- WPGraphQL Docs: https://www.wpgraphql.com/
