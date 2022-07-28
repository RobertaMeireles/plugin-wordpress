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
define( 'DB_NAME', 'plugin' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',         'QBXbuzE8B},M#M(UB-nMc4fgcy[BM,K*$pv*U(x<+L,f_[q$#D{^OBUUzV>:@o>k' );
define( 'SECURE_AUTH_KEY',  'kBNpJPb>6*V%U#OA)&uiToFQ_9?p77+0O)jP[e^V%]Y1P~8a@Gjcp]}Ls7/XeadR' );
define( 'LOGGED_IN_KEY',    'cW`+DmZ4A*,4OY}J?78![I%|k|rEgw<]aw ;S-qzrKDKD10k-;],1mq6W RoGum1' );
define( 'NONCE_KEY',        '2c&bb{h#K#aOm<:><=FV*)gTSumRt1V57bwgO1#;Wxb/VHQ]nh9P>5}H 8Msg`s-' );
define( 'AUTH_SALT',        '1 I$_kxW&jJeB5E{cwO*~E1$Ff=JDjf|h,$s]Sj J;@vfS0yrm>J4gT`;qt`D;:{' );
define( 'SECURE_AUTH_SALT', 'bT<Ag-L{kot7O])H?;%MzAyhK(mudm[b^ATCK]x2$h*4@J)$RDWvGp!)I>TfeKAw' );
define( 'LOGGED_IN_SALT',   'oTJFzXTzkg4R/T>?`2Q4lssXem+1;#)k]vWlV>.d[5k,#2dPQ5S**-R=45h6+1ZM' );
define( 'NONCE_SALT',       'ia3<l_V?QTO%fsUvV[tZLf}}+/m32eX&Zut%DLT>7VY`oZ:s|IFx8yL,]o]o9F<y' );

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
