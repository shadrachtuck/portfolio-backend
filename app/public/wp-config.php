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

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'VO>ABl.p)(Phf.Alw0>O&8D>)6lccq0pQs?ap=f:bSc3Da^ga8-5fen=Fi+#GSdl' );
define( 'SECURE_AUTH_KEY',   'hAid0,)U#6X0!HX6zqW*b]y^sr9TPB!uOsUq015McXQ~vO]!5BlA &[KA OT)F`(' );
define( 'LOGGED_IN_KEY',     'P4(!BaVc7I2ujE~wB*?0PDDW8)6v@?Kryh84W.}`Gn]988IT,$g0KT`zAh6J37/M' );
define( 'NONCE_KEY',         ')!Z+5cWn$^u0od*?1{_)6RjQACkcc!0Y;X^>STo<36!l>G%^PdrBsDyt& yMd<-x' );
define( 'AUTH_SALT',         'w$<h}V6g*5aI{s_M]FR3 Ytq0y?]0|C~>a*q^/kjDpo5C%5|6k]=ze8O69tvP%<[' );
define( 'SECURE_AUTH_SALT',  'vtmim&%gQ&`6{~fJW]d0;~A$lf k6M]Px6txBtFXrxjzsFduh}O[:&#Han[qHHX}' );
define( 'LOGGED_IN_SALT',    't$NXRo?T=qe,~6#L>r&/cg0H8_]|)*P9D74A(s2^1Psn@?=[xAG#~U627 7UQ}XC' );
define( 'NONCE_SALT',        'B/-V%o*l#yl3Yq9GUVNkw+EdZw/~:KT}V8}u{yWJDzG&yI!x^@vo>k)?},<A^Pg`' );
define( 'WP_CACHE_KEY_SALT', 'dkE2ai1ly!JF[=#ugj#avUncG&u1XWlG4*]Gkbiv>cb,JYhjFYUq*Dg]<EPwi/VA' );


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
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
