# Simple SSH Update Instructions

## The Problem
File is owned by `www-data`, so we need to either use sudo (which requires root password) or edit via SSH.

## Solution: Edit File via SSH

### Step 1: SSH into Server
```bash
ssh shadrach@backend.shadrach-tuck.dev
```

### Step 2: Open File in Editor
```bash
nano /var/www/html/portfolio-backend/app/public/wp-content/plugins/mishap-creative-works/mishap-creative-works.php
```

**OR use vi:**
```bash
vi /var/www/html/portfolio-backend/app/public/wp-content/plugins/mishap-creative-works/mishap-creative-works.php
```

### Step 3: Go to End of File
- In **nano:** Press `Ctrl+End` or `Ctrl+V` then scroll down
- In **vi:** Press `Shift+G` (capital G) to go to end

You should see:
```php
add_filter('acf/settings/show_in_rest', '__return_true');
```

### Step 4: Add the New Code
After the line above, press `Enter` to add a new line, then paste this code:

```php

/**
 * Allow public GraphQL access for headless frontend
 * This enables unauthenticated queries to custom post types
 */

// Allow public introspection
add_filter('graphql_allow_introspection', '__return_true');

// Make custom post types publicly accessible in GraphQL
add_filter('register_post_type_args', function($args, $post_type) {
    $custom_post_types = ['web_project', 'repository', 'design_project', 'concert'];
    
    if (in_array($post_type, $custom_post_types)) {
        // Ensure post type is public in GraphQL
        $args['show_in_graphql'] = true;
        $args['public'] = true;
    }
    
    return $args;
}, 10, 2);

// Allow public access to GraphQL queries (disable authentication requirement)
add_filter('graphql_is_private', '__return_false', 10, 1);
add_filter('graphql_should_respect_user_query_visibility', '__return_false', 10, 1);

// Make specific post types public in GraphQL
add_filter('graphql_webProject_is_public', '__return_true');
add_filter('graphql_repository_is_public', '__return_true');
add_filter('graphql_designProject_is_public', '__return_true');
add_filter('graphql_concert_is_public', '__return_true');

// Allow public access to RootQuery fields
add_filter('graphql_field_resolver', function($resolver, $field_name, $type_name) {
    // Allow public access to webProjects, repositories, designProjects
    if ($type_name === 'RootQuery' && in_array($field_name, ['webProjects', 'repositories', 'designProjects', 'concerts'])) {
        return function($source, $args, $context, $info) use ($resolver) {
            // Temporarily set user to null to allow public access
            $original_user = $context->viewer;
            $context->viewer = null;
            $result = $resolver($source, $args, $context, $info);
            $context->viewer = $original_user;
            return $result;
        };
    }
    return $resolver;
}, 10, 3);
```

### Step 5: Save and Exit
- **In nano:** `Ctrl+X`, then `Y`, then `Enter`
- **In vi:** Press `Esc`, type `:wq`, press `Enter`

**Note:** If it says "read-only" or permission denied, you'll need to use `sudo` with root password, OR use hosting panel console/terminal with root access.

## Alternative: Use Hosting Panel Console

If you have access to a hosting panel with console/terminal that has root access:
1. Open console/terminal in hosting panel
2. Run: `sudo nano /var/www/html/portfolio-backend/app/public/wp-content/plugins/mishap-creative-works/mishap-creative-works.php`
3. Add the code above
4. Save

## After Updating

Test GraphQL endpoint - should work without authentication now!
