# Update Plugin via SSH (No Plugin Editor Available)

Since Plugin Editor is disabled, update the file directly via SSH:

## Step 1: SSH into Server

```bash
ssh shadrach@backend.shadrach-tuck.dev
```

## Step 2: Edit the Plugin File

```bash
nano /var/www/html/portfolio-backend/app/public/wp-content/plugins/mishap-creative-works/mishap-creative-works.php
```

**OR use vi:**
```bash
vi /var/www/html/portfolio-backend/app/public/wp-content/plugins/mishap-creative-works/mishap-creative-works.php
```

## Step 3: Navigate to End of File

- Press `Ctrl+End` or `Ctrl+V` then `End` (to go to end)
- OR press `Shift+G` in vi/vim
- OR scroll down to around line 614

## Step 4: Find and Replace

Find this line:
```php
add_filter('acf/settings/show_in_rest', '__return_true');
```

**Delete everything from this line to the end of the file**, then paste the new code from `ADD_TO_PLUGIN.txt`

## Step 5: Save and Exit

- **In nano:** Press `Ctrl+X`, then `Y`, then `Enter`
- **In vi/vim:** Press `Esc`, type `:wq`, then `Enter`

## Alternative: Use SCP to Upload

If you prefer, you can upload the entire file via SCP:

```bash
# From your local machine:
scp portfolio-backend/app/public/wp-content/plugins/mishap-creative-works/mishap-creative-works.php \
    shadrach@backend.shadrach-tuck.dev:/tmp/mishap-creative-works.php

# Then SSH in and move it:
ssh shadrach@backend.shadrach-tuck.dev
# You'll need sudo or root to move it:
# (Ask for root password or use hosting panel console)
```

## Verify After Update

1. Test GraphQL endpoint: https://backend.shadrach-tuck.dev/graphql
2. Try query:
   ```graphql
   {
     webProjects {
       nodes {
         title
       }
     }
   }
   ```
3. Should work without authentication!
