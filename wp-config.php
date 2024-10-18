<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'bookingview' );

define('FS_METHOD', 'direct');

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         '2<qL6Hz@U2(*V`{bXbU3<=7YlNf02,HcG~%fd$#G{$@~9vG8HgXaodi|tQ1j 912' );
define( 'SECURE_AUTH_KEY',  'O K|>;F0cN8MD21Kue^`-W!64-PvQJ|_&pY<%LP5svE{5Z*RUA*k1jd{=jVD8=^`' );
define( 'LOGGED_IN_KEY',    '-41&_9VK0ZO%Tv07Ob[r~@$ufx5c5lp^k]J?q5#HzWP.>(MtjmjHF6JUjhYd;PyE' );
define( 'NONCE_KEY',        'WZ~hd4bkZ&OY>D02&3!V)QF]ZOm[M>:u89G{]o$Cn(q-v;O@.jE}4H]%i~/t!rQ_' );
define( 'AUTH_SALT',        '.?q:}54P~iX)M&*3&3POH1t|pQ9q=P.=O:^[q41f`e6brfFYh07369WI]GFGAOh.' );
define( 'SECURE_AUTH_SALT', '#4GyKSu^={roiQjbp`;@=:o+^w:-l+M|_U/JsUEc>w)voNPvPvq~&}+Uv{M<[mRq' );
define( 'LOGGED_IN_SALT',   'y a[A%_lpaEo@sQi.at<6;e*]%fC(:oc9AKc=1cw5!4i1~(RA~ q79Qbk3]{0V7a' );
define( 'NONCE_SALT',       ':d|t+OW%SK`O?hIXI&{(UW00E~GLwi1:<NXKIj)RP-g@9N2>~V25R,cV>.;C]h~1' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'bv_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */

define( 'WP_DEBUG', false );
define('WP_DEBUG_LOG', true);


/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
