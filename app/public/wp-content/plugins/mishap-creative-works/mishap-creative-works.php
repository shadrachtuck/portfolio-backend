<?php
/**
 * Plugin Name: Mishap Creative Works Portfolio
 * Plugin URI: https://mishapcreativeworks.com
 * Description: Custom post types and fields for Mishap Creative Works portfolio site
 * Version: 1.0.0
 * Author: Mishap Creative Works
 * Author URI: https://mishapcreativeworks.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Custom Post Types
 */
function mishap_register_post_types() {
    // Concert Post Type
    register_post_type('concert', array(
        'labels' => array(
            'name' => 'Concerts',
            'singular_name' => 'Concert',
            'add_new' => 'Add New Concert',
            'add_new_item' => 'Add New Concert',
            'edit_item' => 'Edit Concert',
            'new_item' => 'New Concert',
            'view_item' => 'View Concert',
            'search_items' => 'Search Concerts',
            'not_found' => 'No concerts found',
            'not_found_in_trash' => 'No concerts found in Trash'
        ),
        'public' => true,
        'publicly_queryable' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'concert',
        'graphql_plural_name' => 'concerts',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon' => 'dashicons-microphone',
    ));

    // Design Project Post Type
    register_post_type('design_project', array(
        'labels' => array(
            'name' => 'Design Projects',
            'singular_name' => 'Design Project',
            'add_new' => 'Add New Design Project',
            'add_new_item' => 'Add New Design Project',
            'edit_item' => 'Edit Design Project',
            'new_item' => 'New Design Project',
            'view_item' => 'View Design Project',
            'search_items' => 'Search Design Projects',
            'not_found' => 'No design projects found',
            'not_found_in_trash' => 'No design projects found in Trash'
        ),
        'public' => true,
        'publicly_queryable' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'designProject',
        'graphql_plural_name' => 'designProjects',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon' => 'dashicons-art',
    ));

    // Web Project Post Type
    register_post_type('web_project', array(
        'labels' => array(
            'name' => 'Web Projects',
            'singular_name' => 'Web Project',
            'add_new' => 'Add New Web Project',
            'add_new_item' => 'Add New Web Project',
            'edit_item' => 'Edit Web Project',
            'new_item' => 'New Web Project',
            'view_item' => 'View Web Project',
            'search_items' => 'Search Web Projects',
            'not_found' => 'No web projects found',
            'not_found_in_trash' => 'No web projects found in Trash'
        ),
        'public' => true,
        'publicly_queryable' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'webProject',
        'graphql_plural_name' => 'webProjects',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon' => 'dashicons-laptop',
    ));

    // Repository Post Type
    register_post_type('repository', array(
        'labels' => array(
            'name' => 'Repositories',
            'singular_name' => 'Repository',
            'add_new' => 'Add New Repository',
            'add_new_item' => 'Add New Repository',
            'edit_item' => 'Edit Repository',
            'new_item' => 'New Repository',
            'view_item' => 'View Repository',
            'search_items' => 'Search Repositories',
            'not_found' => 'No repositories found',
            'not_found_in_trash' => 'No repositories found in Trash'
        ),
        'public' => true,
        'publicly_queryable' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'repository',
        'graphql_plural_name' => 'repositories',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon' => 'dashicons-code-standards',
    ));
}
add_action('init', 'mishap_register_post_types');

/**
 * Register Universal Portfolio Tags Taxonomy
 * This taxonomy can be used across all portfolio post types
 */
function mishap_register_portfolio_tags_taxonomy() {
    $labels = array(
        'name' => 'Portfolio Tags',
        'singular_name' => 'Portfolio Tag',
        'search_items' => 'Search Tags',
        'all_items' => 'All Tags',
        'parent_item' => 'Parent Tag',
        'parent_item_colon' => 'Parent Tag:',
        'edit_item' => 'Edit Tag',
        'update_item' => 'Update Tag',
        'add_new_item' => 'Add New Tag',
        'new_item_name' => 'New Tag Name',
        'menu_name' => 'Portfolio Tags',
    );

    $args = array(
        'hierarchical' => false, // Non-hierarchical (like tags, not categories)
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'portfolio-tag'),
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'portfolioTag',
        'graphql_plural_name' => 'portfolioTags',
        'public' => true,
        'publicly_queryable' => true,
    );

    // Register taxonomy for all portfolio post types
    $result = register_taxonomy('portfolio_tag', array('design_project', 'web_project', 'repository', 'concert'), $args);
    
    // #region agent log
    $log_data = array(
        'location' => 'mishap-creative-works.php:159',
        'message' => 'Taxonomy registration result',
        'data' => array(
            'taxonomy' => 'portfolio_tag',
            'post_types' => array('design_project', 'web_project', 'repository', 'concert'),
            'show_in_graphql' => $args['show_in_graphql'],
            'graphql_plural_name' => $args['graphql_plural_name'],
            'graphql_single_name' => $args['graphql_single_name'],
            'result_type' => gettype($result),
            'is_wp_error' => is_wp_error($result)
        ),
        'timestamp' => time() * 1000,
        'sessionId' => 'debug-session',
        'runId' => 'run1',
        'hypothesisId' => 'F'
    );
    file_put_contents('/Users/shadrachtuck/Local Sites/portfolio/.cursor/debug.log', json_encode($log_data) . "\n", FILE_APPEND);
    // #endregion
}
add_action('init', 'mishap_register_portfolio_tags_taxonomy');

/**
 * Ensure portfolio_tag taxonomy is properly exposed in GraphQL
 * This hook runs after GraphQL types are registered to ensure connections exist
 */
function mishap_ensure_portfolio_tags_graphql_connections() {
    $taxonomy = get_taxonomy('portfolio_tag');
    
    if (!$taxonomy) {
        return;
    }
    
    // #region agent log
    $log_data = array(
        'location' => 'mishap-creative-works.php:185',
        'message' => 'Checking taxonomy GraphQL exposure',
        'data' => array(
            'taxonomy' => 'portfolio_tag',
            'show_in_graphql' => $taxonomy->show_in_graphql ?? false,
            'graphql_plural_name' => $taxonomy->graphql_plural_name ?? 'N/A',
            'graphql_single_name' => $taxonomy->graphql_single_name ?? 'N/A',
            'object_type' => $taxonomy->object_type ?? array()
        ),
        'timestamp' => time() * 1000,
        'sessionId' => 'debug-session',
        'runId' => 'run1',
        'hypothesisId' => 'J'
    );
    file_put_contents('/Users/shadrachtuck/Local Sites/portfolio/.cursor/debug.log', json_encode($log_data) . "\n", FILE_APPEND);
    // #endregion
    
    // Force the taxonomy to be exposed in GraphQL if it's not already
    if (!isset($taxonomy->show_in_graphql) || !$taxonomy->show_in_graphql) {
        $taxonomy->show_in_graphql = true;
    }
    
    // Ensure graphql names are set
    if (!isset($taxonomy->graphql_plural_name)) {
        $taxonomy->graphql_plural_name = 'portfolioTags';
    }
    if (!isset($taxonomy->graphql_single_name)) {
        $taxonomy->graphql_single_name = 'portfolioTag';
    }
    
    // Explicitly register GraphQL connections for each post type
    // WPGraphQL should do this automatically, but we'll ensure it happens
    if (function_exists('register_graphql_connection')) {
        $post_type_mappings = array(
            'web_project' => 'WebProject',
            'design_project' => 'DesignProject',
            'repository' => 'Repository',
            'concert' => 'Concert'
        );
        
        foreach ($post_type_mappings as $post_type => $graphql_type) {
            $post_type_obj = get_post_type_object($post_type);
            if ($post_type_obj && isset($post_type_obj->graphql_single_name)) {
                $from_type = $post_type_obj->graphql_single_name;
                $to_type = $taxonomy->graphql_single_name;
                $field_name = $taxonomy->graphql_plural_name;
                
                // Register the connection if it doesn't exist
                register_graphql_connection(array(
                    'fromType' => $from_type,
                    'toType' => $to_type,
                    'fromFieldName' => $field_name,
                    'description' => sprintf('Connection between %s and %s', $from_type, $to_type),
                ));
            }
        }
    }
}
add_action('graphql_register_types', 'mishap_ensure_portfolio_tags_graphql_connections', 20);

/**
 * Flush GraphQL schema cache to ensure taxonomy connections are registered
 * This should be called after taxonomy registration
 */
function mishap_flush_graphql_schema_cache() {
    // Clear WPGraphQL schema cache if the function exists
    if (function_exists('graphql_clear_schema_cache')) {
        graphql_clear_schema_cache();
    }
    
    // Also try to clear any transients that might cache the schema
    delete_transient('wpgraphql_schema');
    delete_transient('graphql_schema');
    
    // #region agent log
    $log_data = array(
        'location' => 'mishap-creative-works.php:220',
        'message' => 'Flushed GraphQL schema cache',
        'data' => array(
            'has_clear_function' => function_exists('graphql_clear_schema_cache')
        ),
        'timestamp' => time() * 1000,
        'sessionId' => 'debug-session',
        'runId' => 'run1',
        'hypothesisId' => 'K'
    );
    file_put_contents('/Users/shadrachtuck/Local Sites/portfolio/.cursor/debug.log', json_encode($log_data) . "\n", FILE_APPEND);
    // #endregion
}
add_action('init', 'mishap_flush_graphql_schema_cache', 999);

/**
 * Register ACF Fields for Concerts
 */
function mishap_register_concert_fields() {
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_concert_fields',
            'title' => 'Concert Details',
            'fields' => array(
                array(
                    'key' => 'field_concert_date',
                    'label' => 'Concert Date',
                    'name' => 'concert_date',
                    'type' => 'date_picker',
                    'required' => 1,
                    'return_format' => 'Y-m-d',
                ),
                array(
                    'key' => 'field_concert_time',
                    'label' => 'Concert Time',
                    'name' => 'concert_time',
                    'type' => 'time_picker',
                ),
                array(
                    'key' => 'field_concert_venue',
                    'label' => 'Venue',
                    'name' => 'venue',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_concert_location',
                    'label' => 'Location',
                    'name' => 'location',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_concert_poster',
                    'label' => 'Show Poster',
                    'name' => 'show_poster',
                    'type' => 'image',
                    'return_format' => 'array',
                ),
                array(
                    'key' => 'field_concert_ticket_link',
                    'label' => 'Ticket Link',
                    'name' => 'ticket_link',
                    'type' => 'url',
                ),
                array(
                    'key' => 'field_is_upcoming',
                    'label' => 'Is Upcoming Concert?',
                    'name' => 'is_upcoming',
                    'type' => 'true_false',
                    'default_value' => 1,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'concert',
                    ),
                ),
            ),
        ));
    }
}
add_action('acf/init', 'mishap_register_concert_fields');

/**
 * Register ACF Fields for Design Projects
 */
function mishap_register_design_project_fields() {
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_design_project_fields',
            'title' => 'Design Project Details',
            'fields' => array(
                array(
                    'key' => 'field_design_category',
                    'label' => 'Category',
                    'name' => 'category',
                    'type' => 'select',
                    'choices' => array(
                        'branding' => 'Branding',
                        'print' => 'Print Design',
                        'digital' => 'Digital Design',
                        'packaging' => 'Packaging',
                        'other' => 'Other',
                    ),
                ),
                array(
                    'key' => 'field_design_client',
                    'label' => 'Client',
                    'name' => 'client',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_design_year',
                    'label' => 'Year',
                    'name' => 'year',
                    'type' => 'number',
                ),
                array(
                    'key' => 'field_design_gallery',
                    'label' => 'Project Gallery',
                    'name' => 'gallery',
                    'type' => 'gallery',
                    'return_format' => 'array',
                ),
                array(
                    'key' => 'field_design_project_url',
                    'label' => 'Project URL',
                    'name' => 'project_url',
                    'type' => 'url',
                ),
                array(
                    'key' => 'field_design_contribution_type_tags',
                    'label' => 'Contribution Type Tags',
                    'name' => 'contribution_type_tags',
                    'type' => 'checkbox',
                    'choices' => array(
                        'software_web' => 'Software/Web',
                        'ux_ui_design' => 'UX/UI Design',
                    ),
                    'allow_custom' => 0,
                    'default_value' => array(),
                    'layout' => 'vertical',
                    'toggle' => 0,
                    'return_format' => 'value',
                    'show_in_graphql' => true,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'design_project',
                    ),
                ),
            ),
        ));
    }
}
add_action('acf/init', 'mishap_register_design_project_fields');

/**
 * Register ACF Fields for Web Projects
 */
function mishap_register_web_project_fields() {
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_web_project_fields',
            'title' => 'Web Project Details',
            'show_in_graphql' => true,
            'fields' => array(
                array(
                    'key' => 'field_web_tech_stack',
                    'label' => 'Technology Stack',
                    'name' => 'tech_stack',
                    'type' => 'repeater',
                    'show_in_graphql' => true,
                    'sub_fields' => array(
                        array(
                            'key' => 'field_tech_name',
                            'label' => 'Technology',
                            'name' => 'tech',
                            'type' => 'text',
                            'show_in_graphql' => true,
                        ),
                    ),
                ),
                array(
                    'key' => 'field_web_client',
                    'label' => 'Client',
                    'name' => 'client',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_web_year',
                    'label' => 'Year',
                    'name' => 'year',
                    'type' => 'number',
                ),
                array(
                    'key' => 'field_web_project_url',
                    'label' => 'Project URL',
                    'name' => 'project_url',
                    'type' => 'url',
                ),
                array(
                    'key' => 'field_web_github_url',
                    'label' => 'GitHub URL',
                    'name' => 'github_url',
                    'type' => 'url',
                ),
                array(
                    'key' => 'field_web_screenshot',
                    'label' => 'Screenshots',
                    'name' => 'screenshots',
                    'type' => 'gallery',
                    'return_format' => 'array',
                    'show_in_graphql' => true,
                ),
                array(
                    'key' => 'field_web_contribution_type_tags',
                    'label' => 'Contribution Type Tags',
                    'name' => 'contribution_type_tags',
                    'type' => 'checkbox',
                    'choices' => array(
                        'software_web' => 'Software/Web',
                        'ux_ui_design' => 'UX/UI Design',
                    ),
                    'allow_custom' => 0,
                    'default_value' => array(),
                    'layout' => 'vertical',
                    'toggle' => 0,
                    'return_format' => 'value',
                    'show_in_graphql' => true,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'web_project',
                    ),
                ),
            ),
        ));
    }
}
add_action('acf/init', 'mishap_register_web_project_fields');

/**
 * Register ACF Fields for Repositories
 */
function mishap_register_repository_fields() {
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_repository_fields',
            'title' => 'Repository Details',
            'show_in_graphql' => true,
            'fields' => array(
                array(
                    'key' => 'field_link_type',
                    'label' => 'Link Type',
                    'name' => 'link_type',
                    'type' => 'select',
                    'choices' => array(
                        'repository' => 'Repository',
                        'site' => 'Site/Other Link',
                    ),
                    'default_value' => 'repository',
                    'required' => 1,
                    'show_in_graphql' => true,
                ),
                array(
                    'key' => 'field_contribution_meta',
                    'label' => 'Contribution Type',
                    'name' => 'contribution_meta',
                    'type' => 'select',
                    'choices' => array(
                        'private_repo' => 'Private Repository',
                        'public_repo' => 'Public Repository',
                        'site' => 'Site',
                        'project' => 'Project',
                    ),
                    'default_value' => 'public_repo',
                    'required' => 1,
                    'show_in_graphql' => true,
                ),
                array(
                    'key' => 'field_repository_url',
                    'label' => 'Repository URL',
                    'name' => 'repository_url',
                    'type' => 'url',
                    'instructions' => 'Optional: Link to the repository (GitHub, GitLab, etc.)',
                    'show_in_graphql' => true,
                ),
                array(
                    'key' => 'field_site_url',
                    'label' => 'Site/About URL',
                    'name' => 'site_url',
                    'type' => 'url',
                    'instructions' => 'Optional: Link to the live site or project page',
                    'show_in_graphql' => true,
                ),
                array(
                    'key' => 'field_repository_year',
                    'label' => 'Year',
                    'name' => 'year',
                    'type' => 'number',
                    'show_in_graphql' => true,
                ),
                array(
                    'key' => 'field_custom_logo',
                    'label' => 'Custom Logo',
                    'name' => 'custom_logo',
                    'type' => 'image',
                    'return_format' => 'array',
                    'preview_size' => 'thumbnail',
                    'library' => 'all',
                    'instructions' => 'Optional: Upload a custom logo to display next to the site/project name',
                    'show_in_graphql' => true,
                ),
                array(
                    'key' => 'field_contribution_type_tags',
                    'label' => 'Contribution Type Tags',
                    'name' => 'contribution_type_tags',
                    'type' => 'checkbox',
                    'choices' => array(
                        'software_web' => 'Software/Web',
                        'ux_ui_design' => 'UX/UI Design',
                    ),
                    'allow_custom' => 0,
                    'default_value' => array(),
                    'layout' => 'vertical',
                    'toggle' => 0,
                    'return_format' => 'value',
                    'show_in_graphql' => true,
                ),
                array(
                    'key' => 'field_repository_platform',
                    'label' => 'Platform',
                    'name' => 'platform',
                    'type' => 'select',
                    'choices' => array(
                        'github' => 'GitHub',
                        'gitlab' => 'GitLab',
                        'bitbucket' => 'Bitbucket',
                        'azure' => 'Azure Repos',
                        'other' => 'Other',
                    ),
                    'default_value' => 'github',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_link_type',
                                'operator' => '==',
                                'value' => 'repository',
                            ),
                        ),
                    ),
                    'show_in_graphql' => true,
                ),
                array(
                    'key' => 'field_repository_language',
                    'label' => 'Primary Language',
                    'name' => 'language',
                    'type' => 'text',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_link_type',
                                'operator' => '==',
                                'value' => 'repository',
                            ),
                        ),
                    ),
                    'show_in_graphql' => true,
                ),
                array(
                    'key' => 'field_repository_stars',
                    'label' => 'Stars',
                    'name' => 'stars',
                    'type' => 'number',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_link_type',
                                'operator' => '==',
                                'value' => 'repository',
                            ),
                        ),
                    ),
                    'show_in_graphql' => true,
                ),
                array(
                    'key' => 'field_repository_contribution_type',
                    'label' => 'Contribution Type',
                    'name' => 'contribution_type',
                    'type' => 'select',
                    'choices' => array(
                        'owner' => 'Owner/Creator',
                        'contributor' => 'Contributor',
                        'maintainer' => 'Maintainer',
                        'collaborator' => 'Collaborator',
                    ),
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_link_type',
                                'operator' => '==',
                                'value' => 'repository',
                            ),
                        ),
                    ),
                    'show_in_graphql' => true,
                ),
                array(
                    'key' => 'field_repository_is_fork',
                    'label' => 'Is Fork?',
                    'name' => 'is_fork',
                    'type' => 'true_false',
                    'default_value' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_link_type',
                                'operator' => '==',
                                'value' => 'repository',
                            ),
                        ),
                    ),
                    'show_in_graphql' => true,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'repository',
                    ),
                ),
            ),
        ));
    }
}
add_action('acf/init', 'mishap_register_repository_fields');

/**
 * Register ACF Fields for About Page
 */
function mishap_register_about_page_fields() {
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_about_page_fields',
            'title' => 'About Page Details',
            'fields' => array(
                array(
                    'key' => 'field_about_hero_image',
                    'label' => 'Hero Image',
                    'name' => 'hero_image',
                    'type' => 'image',
                    'return_format' => 'array',
                ),
                array(
                    'key' => 'field_about_bio',
                    'label' => 'Bio/About Text',
                    'name' => 'bio',
                    'type' => 'wysiwyg',
                    'toolbar' => 'full',
                    'media_upload' => 1,
                ),
                array(
                    'key' => 'field_about_resume',
                    'label' => 'Resume PDF',
                    'name' => 'resume',
                    'type' => 'file',
                    'return_format' => 'array',
                    'mime_types' => 'pdf',
                ),
                array(
                    'key' => 'field_about_gallery',
                    'label' => 'Gallery Images',
                    'name' => 'gallery',
                    'type' => 'gallery',
                    'return_format' => 'array',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'page',
                        'operator' => '==',
                        'value' => 'page',
                    ),
                ),
            ),
        ));
    }
}
add_action('acf/init', 'mishap_register_about_page_fields');

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

// Make post type data public (returns false = not private = public)
add_filter('graphql_data_is_private', function($is_private, $model_name, $data, $visibility, $owner, $current_user) {
    // Allow public access to custom post types
    $public_post_types = ['web_project', 'repository', 'design_project', 'concert'];
    
    if (in_array($model_name, $public_post_types) || (is_object($data) && isset($data->post_type) && in_array($data->post_type, $public_post_types))) {
        return false; // Not private = public
    }
    
    return $is_private;
}, 10, 6);

// Use graphql_object_visibility to force post types to be public
add_filter('graphql_object_visibility', function($visibility, $model_name, $data, $owner, $current_user) {
    $public_post_types = ['webProject', 'repository', 'designProject', 'concert'];
    
    // Check if this is one of our custom post types (GraphQL type names)
    if (in_array($model_name, $public_post_types)) {
        return 'public'; // Force public visibility
    }
    
    // Also check if data object has our post type
    if (is_object($data) && isset($data->post_type)) {
        $post_type_map = ['web_project' => 'webProject', 'repository' => 'repository', 'design_project' => 'designProject', 'concert' => 'concert'];
        if (isset($post_type_map[$data->post_type])) {
            return 'public'; // Force public visibility
        }
    }
    
    return $visibility;
}, 10, 5);

// Allow public access to RootQuery fields by modifying connection query args
add_filter('graphql_connection_query_args', function($query_args, $connection_resolver) {
    // Get the post type from the resolver
    $public_post_types = ['web_project', 'repository', 'design_project', 'concert'];
    
    // Check if query is for one of our public post types
    if (isset($query_args['post_type'])) {
        $post_type = is_array($query_args['post_type']) ? $query_args['post_type'][0] : $query_args['post_type'];
        
        if (in_array($post_type, $public_post_types)) {
            // Ensure status includes publish
            if (!isset($query_args['post_status'])) {
                $query_args['post_status'] = 'publish';
            } else if (is_array($query_args['post_status']) && !in_array('publish', $query_args['post_status'])) {
                $query_args['post_status'][] = 'publish';
            }
            
            // Remove any privacy/visibility restrictions
            $query_args['perm'] = 'readable';
        }
    }
    
    return $query_args;
}, 10, 2);

// Remove authentication requirement from RootQuery fields
// This filter runs when fields are registered
add_filter('graphql_register_field_type_config', function($config, $type_name, $field_name) {
    $public_fields = ['webProjects', 'repositories', 'designProjects', 'concerts'];
    
    if ($type_name === 'RootQuery' && in_array($field_name, $public_fields)) {
        // Remove auth requirement
        unset($config['auth']);
        $config['isPrivate'] = false;
        $config['authCallback'] = function() { return true; }; // Always allow
    }
    
    return $config;
}, 10, 3);
