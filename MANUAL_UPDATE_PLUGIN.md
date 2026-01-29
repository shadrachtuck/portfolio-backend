# Manual Plugin Update (Git Not Working)

Since git pull requires sudo and you don't have the sudo password, here's how to update the plugin file manually:

## Method 1: WordPress Admin Plugin Editor (EASIEST)

1. **Log into WordPress Admin:**
   - Go to: https://backend.shadrach-tuck.dev/wp-admin
   - Log in

2. **Edit Plugin:**
   - Navigate to: **Plugins** → **Plugin Editor**
   - Select: **Mishap Creative Works Portfolio**
   - This opens: `mishap-creative-works.php`

3. **Scroll to the bottom** (around line 614)
   - Find the section starting with `add_filter('acf/settings/show_in_rest'...`

4. **Replace everything from there to the end** with the code from:
   - `GRAPHQL_FIX_CODE.php` file
   - Or copy the code I'll provide below

5. **Click "Update File"**

## Method 2: Edit File Directly via SSH

1. **SSH into server:**
   ```bash
   ssh shadrach@backend.shadrach-tuck.dev
   ```

2. **Edit the file:**
   ```bash
   nano /var/www/html/portfolio-backend/app/public/wp-content/plugins/mishap-creative-works/mishap-creative-works.php
   ```
   OR use `vi` or your preferred editor

3. **Scroll to line 614** (or near the end)
   - Find: `add_filter('acf/settings/show_in_rest', '__return_true');`
   - Delete everything from that line to the end
   - Paste the new code from `GRAPHQL_FIX_CODE.php`

4. **Save and exit**

## Method 3: Upload File via SFTP

1. **Use SFTP client** (FileZilla, Cyberduck, etc.)
2. **Connect to:** `backend.shadrach-tuck.dev`
3. **Upload:** `app/public/wp-content/plugins/mishap-creative-works/mishap-creative-works.php`
4. **To:** `/var/www/html/portfolio-backend/app/public/wp-content/plugins/mishap-creative-works/`

## Code to Add

See `GRAPHQL_FIX_CODE.php` for the complete code block to add to the end of your plugin file.

## After Updating

1. **Test GraphQL endpoint:**
   ```graphql
   {
     webProjects {
       nodes {
         title
       }
     }
   }
   ```
2. **Should work without authentication now!**
