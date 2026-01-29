<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// Load environment configuration from .env files
require_once __DIR__ . '/wp-config.env.php';

// ** Database settings - Loaded from .env file ** //
/** The name of the database for WordPress */
if (!defined('DB_NAME')) {
	define( 'DB_NAME', 'local' );
}

/** Database username - Loaded from .env file */
if (!defined('DB_USER')) {
	define( 'DB_USER', 'root' );
}

/** Database password - Loaded from .env file */
if (!defined('DB_PASSWORD')) {
	define( 'DB_PASSWORD', 'root' );
}

/** Database hostname - Loaded from .env file */
if (!defined('DB_HOST')) {
	define( 'DB_HOST', 'localhost' );
}

/** Database charset to use in creating database tables - Loaded from .env file */
if (!defined('DB_CHARSET')) {
	define( 'DB_CHARSET', 'utf8mb4' );
}

/** The database collate type. Don't change this if in doubt - Loaded from .env file */
if (!defined('DB_COLLATE')) {
	define( 'DB_COLLATE', '' );
}

/**#@+
 * Authentication unique keys and salts - Loaded from .env file
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
// Keys are loaded from .env file via wp-config.env.php
if (!defined('WP_CACHE_KEY_SALT')) {
	define( 'WP_CACHE_KEY_SALT', 'dkE2ai1ly!JF[=#ugj#avUncG&u1XWlG4*]Gkbiv>cb,JYhjFYUq*Dg]<EPwi/VA' );
}


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 *
 * Debug settings are loaded from .env file via wp-config.env.php
 */
// Debug settings are loaded from .env file

// Environment type is loaded from .env file
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
