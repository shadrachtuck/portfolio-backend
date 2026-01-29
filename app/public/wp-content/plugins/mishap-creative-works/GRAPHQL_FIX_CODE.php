<?php
/**
 * COPY THIS CODE TO THE END OF YOUR PLUGIN FILE
 * File: mishap-creative-works.php
 * Add after line 614 (after the ACF show_in_rest filter)
 */

/**
 * Enable ACF fields in GraphQL and REST
 * Note: This requires the WPGraphQL for Advanced Custom Fields plugin
 * to be installed for ACF fields to appear in GraphQL queries
 */
add_filter('acf/settings/show_in_rest', '__return_true');

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
