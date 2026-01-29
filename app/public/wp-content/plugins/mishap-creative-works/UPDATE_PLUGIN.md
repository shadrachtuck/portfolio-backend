# Update Plugin File to Fix GraphQL Authentication

## The Issue
GraphQL requires authentication but we need public access for the headless frontend.

## Solution: Update Plugin File

The plugin file has been updated with filters to allow public GraphQL access. You need to upload it to production.

## Upload Methods

### Method 1: WordPress Admin File Editor (EASIEST)

1. **Log into WordPress Admin:**
   - Go to: https://backend.shadrach-tuck.dev/wp-admin
   - Log in

2. **Edit Plugin File:**
   - Navigate to: **Plugins** → **Plugin Editor**
   - Select: **Mishap Creative Works Portfolio**
   - This will open: `mishap-creative-works.php`

3. **Replace the bottom section:**
   - Find the section starting with `add_filter('acf/settings/show_in_rest'...`
   - Replace everything from there to the end with the updated code from the local file

### Method 2: Upload via SFTP/File Manager

1. **Via SFTP client or hosting panel File Manager:**
   - Upload: `portfolio-backend/app/public/wp-content/plugins/mishap-creative-works/mishap-creative-works.php`
   - To: `/var/www/html/portfolio-backend/app/public/wp-content/plugins/mishap-creative-works/`
   - Replace the existing file

### Method 3: SSH with Root Access

If you have root access via server console:

```bash
# Copy file to /tmp first
# Then move with sudo:
sudo cp /tmp/mishap-creative-works.php /var/www/html/portfolio-backend/app/public/wp-content/plugins/mishap-creative-works/
sudo chown www-data:www-data /var/www/html/portfolio-backend/app/public/wp-content/plugins/mishap-creative-works/mishap-creative-works.php
```

## After Upload

The plugin will automatically activate the new filters. Test the GraphQL endpoint:

```graphql
{
  webProjects {
    nodes {
      title
    }
  }
}
```

Should work without authentication now.
