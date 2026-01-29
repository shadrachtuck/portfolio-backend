# Password Configuration Check

## Current Configurations

### Local Database (wp-config.php)
- **DB_NAME:** `local`
- **DB_USER:** `root`
- **DB_PASSWORD:** `root`
- **DB_HOST:** `localhost`

### Production Database
Production database credentials should be set in the production `wp-config.php` on the server. To check:

```bash
ssh shadrach@backend.shadrach-tuck.dev
cat /var/www/html/portfolio-backend/app/public/wp-config.php | grep -E "DB_NAME|DB_USER|DB_PASSWORD|DB_HOST"
```

### SSH Authentication
- **Status:** ✅ Working (SSH keys configured, no password needed)
- **User:** `shadrach@backend.shadrach-tuck.dev`

### Git Authentication
- **Status:** ✅ Using macOS Keychain (`osxkeychain`)
- **Remote:** `https://github.com/shadrachtuck/portfolio-backend.git`
- Credentials are stored in macOS Keychain

## To Check Production Database Password

1. **SSH into server:**
   ```bash
   ssh shadrach@backend.shadrach-tuck.dev
   ```

2. **View wp-config.php (production):**
   ```bash
   cat /var/www/html/portfolio-backend/app/public/wp-config.php | grep -E "DB_"
   ```

3. **Check if database connection works:**
   ```bash
   mysql -u [DB_USER] -p -h [DB_HOST] [DB_NAME]
   ```

## Common Issues

- **Production wp-config.php has local credentials** - Need to update with production database info
- **Database password incorrect** - WordPress won't connect to database
- **SSH password required** - SSH keys not properly configured
