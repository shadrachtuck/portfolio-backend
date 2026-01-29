# Quick Start - Backend Environment Setup

## ✅ What's Been Set Up

1. **Environment Configuration System**
   - Created `wp-config.env.php` - Environment loader that reads from .env files
   - Updated `wp-config.php` - Now uses environment variables
   - Created `.env.example` - Template for environment variables
   - Created `.env.local` - Local development configuration (git-ignored)

2. **Git Branches**
   - `main` - Production branch
   - `development` - Development branch (currently checked out)

## 🚀 Quick Start

### For Local Development

1. **Verify `.env.local` exists and has correct values:**
   ```bash
   cat .env.local
   ```

2. **The WordPress site will automatically use `.env.local` for configuration!**

3. **No restart needed** - WordPress loads config on each request

### For Production

1. **On production server, create `.env.production`:**
   ```bash
   cp .env.example .env.production
   ```

2. **Edit `.env.production` with production values:**
   - Database credentials
   - WordPress URLs (https://backend.shadrach-tuck.dev)
   - Generate new security keys
   - Set `WP_ENV=production`
   - Set `WP_DEBUG=false`

3. **The WordPress site will automatically use `.env.production` for configuration!**

## 📝 How It Works

The system automatically detects which environment file to use:
- If `.env.production` exists → Production mode
- If `.env.local` exists → Development mode
- Falls back to defaults if neither exists

## 🔄 Git Workflow

```bash
# Work on development branch
git checkout development

# When ready for production
git checkout main
git merge development
git push origin main  # Deploy to production server
```

## 📚 More Details

See `BACKEND_ENVIRONMENT_SETUP.md` for complete documentation.
