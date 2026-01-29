# WordPress Backend Environment Configuration

This guide covers setting up development and production environments for the WordPress backend.

## Environment Files

### Available Files

1. **`.env.example`** - Template showing all available environment variables (committed to git)
2. **`.env.local`** - Local development configuration (git-ignored, create from `.env.example`)
3. **`.env.production`** - Production configuration (git-ignored, create from `.env.example`)

### How It Works

The `wp-config.php` file now includes `wp-config.env.php` which:
- Automatically loads `.env.production` if it exists (production)
- Falls back to `.env.local` if it exists (development)
- Sets all WordPress constants from environment variables

## Setup Instructions

### Local Development

1. **Copy `.env.example` to `.env.local`:**
   ```bash
   cp .env.example .env.local
   ```

2. **Edit `.env.local` with your local settings:**
   - Database credentials (usually `root`/`root` for Local by Flywheel)
   - WordPress URLs (your local domain)
   - Security keys (can reuse existing ones for local)

3. **The configuration will be automatically loaded!**

### Production

1. **Create `.env.production` on your production server:**
   ```bash
   # On production server
   cp .env.example .env.production
   ```

2. **Edit `.env.production` with production values:**
   - Production database credentials
   - Production WordPress URLs (https://backend.shadrach-tuck.dev)
   - **Generate new security keys** from https://api.wordpress.org/secret-key/1.1/salt/
   - Set `WP_ENV=production`
   - Set `WP_DEBUG=false`
   - Set `DISALLOW_FILE_EDIT=true`
   - Set `FORCE_SSL_ADMIN=true`

3. **The configuration will be automatically loaded!**

## Environment Variables

### Required Variables

- `WP_ENV` - Environment type (development/production)
- `DB_NAME` - Database name
- `DB_USER` - Database username
- `DB_PASSWORD` - Database password
- `DB_HOST` - Database host
- `WP_HOME` - WordPress home URL
- `WP_SITEURL` - WordPress site URL

### Security Keys

Generate new keys for production:
- Visit: https://api.wordpress.org/secret-key/1.1/salt/
- Copy the generated keys to your `.env.production` file

### Debug Settings

**Development (.env.local):**
```env
WP_DEBUG=true
WP_DEBUG_LOG=true
WP_DEBUG_DISPLAY=true
SCRIPT_DEBUG=true
```

**Production (.env.production):**
```env
WP_DEBUG=false
WP_DEBUG_LOG=false
WP_DEBUG_DISPLAY=false
SCRIPT_DEBUG=false
```

## Git Branches

### Branch Strategy

- **`main`** - Production branch (deployed to production server)
- **`development`** - Development branch (local development)

### Workflow

1. Work on the `development` branch for local development
2. Merge `development` → `main` when ready for production
3. Deploy `main` branch to production server

### Creating/Switching Branches

```bash
# Create and switch to development branch
git checkout -b development

# Switch to main branch
git checkout main

# Switch to development branch
git checkout development
```

## Deployment

### Production Deployment Checklist

1. ✅ Ensure `.env.production` exists on production server
2. ✅ Verify all production environment variables are set
3. ✅ Generate new security keys for production
4. ✅ Set `WP_ENV=production`
5. ✅ Disable debug mode (`WP_DEBUG=false`)
6. ✅ Enable security settings (`DISALLOW_FILE_EDIT=true`, `FORCE_SSL_ADMIN=true`)
7. ✅ Test GraphQL endpoint after deployment
8. ✅ Clear GraphQL schema cache if needed

## Troubleshooting

### Environment variables not loading?

1. **Check file location**: `.env.local` or `.env.production` must be in the project root (`portfolio-backend/`)
2. **Check file permissions**: Ensure the file is readable by the web server
3. **Check syntax**: Ensure no syntax errors in the `.env` file
4. **Clear WordPress cache**: Some caching plugins may cache configuration

### Database connection errors?

1. Verify database credentials in `.env` file
2. Check database host (usually `localhost` for local, may differ for production)
3. Ensure database exists and user has proper permissions

### URLs not working?

1. Verify `WP_HOME` and `WP_SITEURL` in `.env` file
2. Check that URLs match your actual domain
3. For production, ensure HTTPS is used

## Security Notes

- **Never commit `.env.local` or `.env.production`** - they're git-ignored
- **Generate new security keys for production** - don't reuse development keys
- **Use strong database passwords in production**
- **Enable `DISALLOW_FILE_EDIT` in production** to prevent file editing via WordPress admin
- **Enable `FORCE_SSL_ADMIN` in production** to force HTTPS for admin area

## Files Structure

```
portfolio-backend/
├── .env.example          # Template (committed)
├── .env.local            # Local dev config (git-ignored)
├── .env.production       # Production config (git-ignored, on server only)
└── app/
    └── public/
        ├── wp-config.php          # Main config (includes env loader)
        └── wp-config.env.php      # Environment loader (committed)
```
