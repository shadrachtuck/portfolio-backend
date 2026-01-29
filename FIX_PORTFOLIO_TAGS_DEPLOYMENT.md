# Fix: Portfolio Tags GraphQL Connection - Production Deployment

## Problem
Production GraphQL endpoint is returning error:
```
Cannot query field "portfolioTags" on type "WebProject"
```

## Root Cause
The `portfolioTags` taxonomy connection is not being exposed in GraphQL on the production WordPress backend. This happens when:
1. The updated plugin code hasn't been deployed to production
2. The GraphQL schema cache hasn't been cleared
3. The taxonomy connections aren't being registered properly

## Solution Applied
Updated `mishap-creative-works.php` plugin to:
1. Explicitly register GraphQL connections between post types and the taxonomy
2. Ensure taxonomy is properly exposed in GraphQL
3. Clear GraphQL schema cache after registration

## Deployment Steps

### Option 1: SSH Update (Recommended)
If you have SSH access to production:

```bash
# Navigate to your local plugin directory
cd "/Users/shadrachtuck/Local Sites/portfolio/portfolio-backend/app/public/wp-content/plugins/mishap-creative-works"

# Upload the updated plugin file
scp mishap-creative-works.php user@your-server:/path/to/wp-content/plugins/mishap-creative-works/
```

Then SSH into production and:
1. Clear GraphQL schema cache (if WPGraphQL has a cache clear function)
2. Or visit WordPress admin → WPGraphQL → Settings → Clear Schema Cache
3. Or deactivate and reactivate the plugin

### Option 2: WordPress Admin Upload
1. Zip the `mishap-creative-works` plugin folder
2. Go to WordPress Admin → Plugins → Add New → Upload Plugin
3. Upload and replace the existing plugin
4. Clear GraphQL schema cache

### Option 3: SFTP/File Manager
1. Upload `mishap-creative-works.php` to production via SFTP or hosting file manager
2. Replace the existing file at:
   `wp-content/plugins/mishap-creative-works/mishap-creative-works.php`
3. Clear GraphQL schema cache

## After Deployment

### Clear GraphQL Schema Cache
The schema cache must be cleared for changes to take effect:

**Method 1: WordPress Admin**
1. Go to WordPress Admin → GraphQL → Settings
2. Look for "Clear Schema Cache" or "Regenerate Schema" button
3. Click it

**Method 2: Via Code (if you have access)**
Add this temporarily to your `functions.php` or run via WP-CLI:
```php
if (function_exists('graphql_clear_schema_cache')) {
    graphql_clear_schema_cache();
}
```

**Method 3: Deactivate/Reactivate Plugin**
1. Go to Plugins → Installed Plugins
2. Deactivate "Mishap Creative Works Portfolio"
3. Reactivate it
4. This will trigger schema regeneration

### Verify Fix
1. Test the GraphQL endpoint:
   ```graphql
   query {
     webProjects(first: 1) {
       nodes {
         id
         title
         portfolioTags {
           nodes {
             id
             name
           }
         }
       }
     }
   }
   ```

2. The `portfolioTags` field should now be available on:
   - `WebProject`
   - `DesignProject`
   - `Repository`
   - `Concert`

## Files Changed
- `app/public/wp-content/plugins/mishap-creative-works/mishap-creative-works.php`
  - Added explicit GraphQL connection registration
  - Enhanced taxonomy GraphQL exposure checks
  - Added schema cache flushing

## Notes
- The fix ensures taxonomy connections are explicitly registered
- Schema cache must be cleared for changes to take effect
- The connection should work automatically after cache clear
- If issues persist, check that WPGraphQL plugin is up to date
