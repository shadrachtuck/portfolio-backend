# Git-Based Deployment Guide

This guide covers deploying updates to production using Git instead of SCP or manual file editing.

## Prerequisites

- Git repository is set up and connected to GitHub
- SSH access to production server
- Git is installed on production server

## Current Setup

- **Repository:** `https://github.com/shadrachtuck/portfolio-backend.git`
- **Production Path:** `/var/www/html/portfolio-backend/`
- **Plugin Path:** `/var/www/html/portfolio-backend/app/public/wp-content/plugins/mishap-creative-works/`

## Deployment Workflow

### Step 1: Commit Changes Locally

```bash
# Navigate to backend directory
cd "/Users/shadrachtuck/Local Sites/portfolio/portfolio-backend"

# Check what's changed
git status

# Add the plugin file (or other changed files)
git add app/public/wp-content/plugins/mishap-creative-works/mishap-creative-works.php

# Commit with a descriptive message
git commit -m "Add CORS headers for GraphQL API"

# Push to GitHub
git push origin development  # or main, depending on your branch
```

### Step 2: Deploy to Production Server

**Option A: Manual Pull (Recommended for now)**

```bash
# SSH into production server
ssh shadrach@backend.shadrach-tuck.dev

# Navigate to the repository directory
cd /var/www/html/portfolio-backend

# Pull latest changes
git pull origin main  # or development, depending on your workflow

# If you get permission errors, you may need to:
sudo git pull origin main
# OR change ownership temporarily
sudo chown -R shadrach:shadrach /var/www/html/portfolio-backend
git pull origin main
sudo chown -R www-data:www-data /var/www/html/portfolio-backend
```

**Option B: Automated Deployment Script**

Create a deployment script on the server:

```bash
# On production server, create: /home/shadrach/deploy-backend.sh
#!/bin/bash
cd /var/www/html/portfolio-backend
git pull origin main
# Clear any caches if needed
# Restart services if needed
echo "Deployment complete!"
```

Make it executable:
```bash
chmod +x /home/shadrach/deploy-backend.sh
```

Then deploy with:
```bash
ssh shadrach@backend.shadrach-tuck.dev '/home/shadrach/deploy-backend.sh'
```

**Option C: Git Post-Receive Hook (Advanced)**

Set up a git hook on the server to automatically deploy when you push:

```bash
# On production server
cd /var/www/html/portfolio-backend
mkdir -p .git/hooks
cat > .git/hooks/post-receive << 'EOF'
#!/bin/bash
cd /var/www/html/portfolio-backend
git --git-dir=/var/www/html/portfolio-backend/.git --work-tree=/var/www/html/portfolio-backend checkout -f
# Clear caches, restart services, etc.
EOF
chmod +x .git/hooks/post-receive
```

## Recommended Git Workflow

### Branch Strategy

1. **Development Branch** (`development`)
   - Work on features and fixes here
   - Test locally
   - Push to GitHub

2. **Main Branch** (`main`)
   - Production-ready code
   - Deploy from this branch to production

### Typical Workflow

```bash
# 1. Work on development branch
git checkout development
# ... make changes ...
git add .
git commit -m "Description of changes"
git push origin development

# 2. When ready for production, merge to main
git checkout main
git merge development
git push origin main

# 3. Deploy to production server
ssh shadrach@backend.shadrach-tuck.dev
cd /var/www/html/portfolio-backend
git pull origin main
```

## Quick Deploy Commands

### Deploy Plugin Update Only

```bash
# Local: Commit and push
cd "/Users/shadrachtuck/Local Sites/portfolio/portfolio-backend"
git add app/public/wp-content/plugins/mishap-creative-works/mishap-creative-works.php
git commit -m "Update plugin: Add CORS support"
git push origin main

# Production: Pull changes
ssh shadrach@backend.shadrach-tuck.dev "cd /var/www/html/portfolio-backend && git pull origin main"
```

### One-Line Deploy Script

Create a local script `deploy.sh`:

```bash
#!/bin/bash
cd "/Users/shadrachtuck/Local Sites/portfolio/portfolio-backend"
git add app/public/wp-content/plugins/mishap-creative-works/mishap-creative-works.php
git commit -m "$1"
git push origin main
echo "Pushed to GitHub. Now pulling on server..."
ssh shadrach@backend.shadrach-tuck.dev "cd /var/www/html/portfolio-backend && git pull origin main"
echo "Deployment complete!"
```

Usage:
```bash
chmod +x deploy.sh
./deploy.sh "Add CORS headers"
```

## Handling File Permissions

If you encounter permission errors on the server:

```bash
# Option 1: Change ownership for git operations
sudo chown -R shadrach:shadrach /var/www/html/portfolio-backend
git pull origin main
sudo chown -R www-data:www-data /var/www/html/portfolio-backend

# Option 2: Use sudo for git pull
sudo -u www-data git pull origin main

# Option 3: Add your user to www-data group
sudo usermod -a -G www-data shadrach
# Then logout and login again
```

## Troubleshooting

### Git Pull Fails Due to Local Changes

If the server has uncommitted changes:

```bash
# Stash local changes
git stash
git pull origin main
git stash pop

# OR discard local changes (be careful!)
git reset --hard origin/main
```

### File Ownership Issues

After pulling, ensure WordPress can write to necessary directories:

```bash
sudo chown -R www-data:www-data /var/www/html/portfolio-backend/app/public/wp-content
sudo chmod -R 755 /var/www/html/portfolio-backend/app/public/wp-content
sudo chmod -R 775 /var/www/html/portfolio-backend/app/public/wp-content/uploads
```

### Clear WordPress Cache After Deployment

```bash
# If using a caching plugin, clear cache
# Or restart PHP-FPM
sudo systemctl restart php8.1-fpm  # Adjust version as needed
```

## Security Notes

- Never commit sensitive files (`.env.production`, database credentials)
- Use `.gitignore` to exclude sensitive files
- Consider using environment variables for production secrets
- Review changes before pushing to main branch

## Benefits of Git Deployment

✅ Version control and history
✅ Easy rollback if something breaks
✅ Can review changes before deploying
✅ Works with CI/CD pipelines
✅ Multiple developers can collaborate
✅ No need for SCP or manual file editing
