<?php
/**
 * WordPress Environment Configuration Loader
 * 
 * Loads configuration from .env files based on environment
 * This file should be included at the top of wp-config.php
 */

// Determine environment
$env_file = __DIR__ . '/../../.env.local';
$env = 'development';

// Check for production environment file first
if (file_exists(__DIR__ . '/../../.env.production')) {
    $env_file = __DIR__ . '/../../.env.production';
    $env = 'production';
} elseif (file_exists(__DIR__ . '/../../.env.local')) {
    $env_file = __DIR__ . '/../../.env.local';
    $env = 'development';
}

// Load environment variables
if (file_exists($env_file)) {
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Set as environment variable if not already set
            if (!isset($_ENV[$key])) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
}

// Helper function to get env var with default
function get_env($key, $default = '') {
    return isset($_ENV[$key]) ? $_ENV[$key] : (getenv($key) ?: $default);
}

// Set WordPress constants from environment variables
if (!defined('WP_ENV')) {
    define('WP_ENV', get_env('WP_ENV', $env));
}

// Database configuration
if (!defined('DB_NAME')) {
    define('DB_NAME', get_env('DB_NAME', 'local'));
}
if (!defined('DB_USER')) {
    define('DB_USER', get_env('DB_USER', 'root'));
}
if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', get_env('DB_PASSWORD', 'root'));
}
if (!defined('DB_HOST')) {
    define('DB_HOST', get_env('DB_HOST', 'localhost'));
}
if (!defined('DB_CHARSET')) {
    define('DB_CHARSET', get_env('DB_CHARSET', 'utf8mb4'));
}
if (!defined('DB_COLLATE')) {
    define('DB_COLLATE', get_env('DB_COLLATE', ''));
}

// WordPress URLs
if (!defined('WP_HOME')) {
    define('WP_HOME', get_env('WP_HOME', 'http://portfolio-backend.local'));
}
if (!defined('WP_SITEURL')) {
    define('WP_SITEURL', get_env('WP_SITEURL', get_env('WP_HOME', 'http://portfolio-backend.local')));
}

// Security keys
if (!defined('AUTH_KEY')) {
    define('AUTH_KEY', get_env('AUTH_KEY', 'put your unique phrase here'));
}
if (!defined('SECURE_AUTH_KEY')) {
    define('SECURE_AUTH_KEY', get_env('SECURE_AUTH_KEY', 'put your unique phrase here'));
}
if (!defined('LOGGED_IN_KEY')) {
    define('LOGGED_IN_KEY', get_env('LOGGED_IN_KEY', 'put your unique phrase here'));
}
if (!defined('NONCE_KEY')) {
    define('NONCE_KEY', get_env('NONCE_KEY', 'put your unique phrase here'));
}
if (!defined('AUTH_SALT')) {
    define('AUTH_SALT', get_env('AUTH_SALT', 'put your unique phrase here'));
}
if (!defined('SECURE_AUTH_SALT')) {
    define('SECURE_AUTH_SALT', get_env('SECURE_AUTH_SALT', 'put your unique phrase here'));
}
if (!defined('LOGGED_IN_SALT')) {
    define('LOGGED_IN_SALT', get_env('LOGGED_IN_SALT', 'put your unique phrase here'));
}
if (!defined('NONCE_SALT')) {
    define('NONCE_SALT', get_env('NONCE_SALT', 'put your unique phrase here'));
}

// Debug settings (environment-based)
$is_production = (WP_ENV === 'production' || get_env('WP_ENV') === 'production');
if (!defined('WP_DEBUG')) {
    define('WP_DEBUG', $is_production ? false : (get_env('WP_DEBUG', 'true') === 'true'));
}
if (!defined('WP_DEBUG_LOG')) {
    define('WP_DEBUG_LOG', $is_production ? false : (get_env('WP_DEBUG_LOG', 'true') === 'true'));
}
if (!defined('WP_DEBUG_DISPLAY')) {
    define('WP_DEBUG_DISPLAY', $is_production ? false : (get_env('WP_DEBUG_DISPLAY', 'true') === 'true'));
}
if (!defined('SCRIPT_DEBUG')) {
    define('SCRIPT_DEBUG', $is_production ? false : (get_env('SCRIPT_DEBUG', 'true') === 'true'));
}

// Performance
if (!defined('WP_MEMORY_LIMIT')) {
    define('WP_MEMORY_LIMIT', get_env('WP_MEMORY_LIMIT', '256M'));
}
if (!defined('WP_MAX_MEMORY_LIMIT')) {
    define('WP_MAX_MEMORY_LIMIT', get_env('WP_MAX_MEMORY_LIMIT', '512M'));
}

// Security settings
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', $is_production ? true : (get_env('DISALLOW_FILE_EDIT', 'false') === 'true'));
}
if (!defined('FORCE_SSL_ADMIN')) {
    define('FORCE_SSL_ADMIN', $is_production ? true : (get_env('FORCE_SSL_ADMIN', 'false') === 'true'));
}

// WordPress environment type
if (!defined('WP_ENVIRONMENT_TYPE')) {
    define('WP_ENVIRONMENT_TYPE', WP_ENV);
}
