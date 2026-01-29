# Deploy Plugin Fix via Git

## Steps

### Step 1: Push to GitHub
```bash
cd portfolio-backend
git push
```

### Step 2: Pull on Production Server
SSH into your server and pull the changes:

```bash
ssh shadrach@backend.shadrach-tuck.dev
cd /var/www/html/portfolio-backend
git pull
```

Or if you need to specify the branch:
```bash
git pull origin main
```

### Step 3: Verify File Updated
```bash
# Check the file was updated
tail -30 wp-content/plugins/mishap-creative-works/mishap-creative-works.php
```

### Step 4: Test GraphQL
The plugin changes should take effect immediately. Test:

```graphql
{
  webProjects {
    nodes {
      title
    }
  }
}
```

Should work without authentication now!

## Troubleshooting

If git pull fails with permission issues:
```bash
# Make sure www-data can write to the directory
sudo chown -R www-data:www-data /var/www/html/portfolio-backend
# Or pull as root and fix ownership after
```
