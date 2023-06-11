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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'dedmenxd' );

/** Database username */
define( 'DB_USER', 'bazaclicker' );

/** Database password */
define( 'DB_PASSWORD', 'hP6pUptQcc' );

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
define( 'AUTH_KEY',         '5`ttOk/>EozuXf|e4e1b-3V}V+*1mDk;W+P>WR/BW[dU#,x0k:<PoQ+AJ q`-[0*' );
define( 'SECURE_AUTH_KEY',  '>JZ,pRvFLw_ [ZA2q;Bp&B$`eAtQ7nGn7Z-Klvqw/O1DB&#MxvGfCF~mD8;+G-[K' );
define( 'LOGGED_IN_KEY',    '~eGW4,PjTW<5?{Z>sALTFH{r,$)5TqLsu lh(M`h:u|A`cB,e5RH16Lo,~AH#+Bl' );
define( 'NONCE_KEY',        'N[Ny9+ic$FQyUzVB51>cPoNFf-SZ+Zw4ev%3<{[-_MljdLxqILj0p1^JQuPQQi|D' );
define( 'AUTH_SALT',        '.4w26-4*.nh-*@oSY-GFqF%:KxVA]7i}MlR{RE5h:n>%i(-+dX}2bOOT0&8,UGzp' );
define( 'SECURE_AUTH_SALT', 'r[w:Y+re:6dn#r{FEbndC13tbT<!ji;Q?7w ik^?$4}I*.K>c[&<ICyZn0[[3H@~' );
define( 'LOGGED_IN_SALT',   '2^6~.,&{~.`sSq`:F;P!mn~v3;!b%{;M?H6h^r@ U.$*KV=;xXuj@T8F5[ 89*9U' );
define( 'NONCE_SALT',       'D%GRz(S*w}D`LXX>Ddi4!0C7p:ZMtp&!#D`9v&MR=&D0 C&ogMyrk22su!W>kSD4' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_clicker';

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
