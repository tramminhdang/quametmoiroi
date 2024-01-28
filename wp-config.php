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
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'itbooks' );

/** Database username */
define( 'DB_USER', 'minhtram' );

/** Database password */
define( 'DB_PASSWORD', 'Dangminhtram@123' );

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
define( 'AUTH_KEY',         '!zq]1oo#8Xc5h`|AE]lQc/4fK78mb>rtX)zg>gs0W&[6DwQL8V=<|x9|Fr8!.N[z' );
define( 'SECURE_AUTH_KEY',  'L1x^<S=rGIn6Z0m28Xv3x.#,4z](UIE*gpUDMDpF8`}1IN!fA(KR_[`d[p/jFxld' );
define( 'LOGGED_IN_KEY',    'JV$m@jSx a_ZkAdiw0^C.,&co%m=V]&y$7dHA.R3y,ptNw/rlo(H(&e1-1jrD}#I' );
define( 'NONCE_KEY',        'q%^p^)cEHozg~&GJ]*pT[V1#L)NvO^7S@]&l^liZ+P`R1A}vjy!mw=*^S HS[Fy>' );
define( 'AUTH_SALT',        'U|w?OMSWY_$KRD,k|v<b(h~qPAO``zIBbwhP$h)uyS@ncFjC`8D(aeyL`L.|T7GJ' );
define( 'SECURE_AUTH_SALT', 'P#7J1379pa?Gh2mrx@GI>bg/N2(N/+|>i8@3C>:V|Cv]n9XR_:Hagofs/XE]#*7T' );
define( 'LOGGED_IN_SALT',   '~wWfK%,Xor,^t{Ov;>?U$zp/v8lP(U].48>Ir1]3+v;na:V/V>J^5WkB[A2c.Z9B' );
define( 'NONCE_SALT',       'jRZIqnt;_?%XP1(ZRrdA9vgHBB Sq}]t*<FmR_q!Q|WS39Zyn9(Q`jaac.e18GOv' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
