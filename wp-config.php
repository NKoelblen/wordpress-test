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
define( 'DB_NAME', 'wordpress-test' );

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
define( 'AUTH_KEY',         'xZ@^:4{y/dW7xe9;MX 5^_XfGPC #Rz<1ZaMD5{o5*WKyMI8RCT<#UDZlq5Y4>Br' );
define( 'SECURE_AUTH_KEY',  ';1[s:v%3+8gQ8N:l0xfZ/VE~{SS#L9e4X(^cQ`B(A`(f;ZVKw9g@TPj_n1W05Pd=' );
define( 'LOGGED_IN_KEY',    'ieL#Dl<mIS}o@6_&%7O;>[]Z]Z<YZY(d0Vp,8zk^?SnV;cIO;HFCKJAJ7R~MF&n.' );
define( 'NONCE_KEY',        '}fM3Wbe1rS; L3V?*F;E@7|gB8a8b{sC0Nx@}p5 s#t1a?XhU=fs*bJK+HGNzH^A' );
define( 'AUTH_SALT',        'V6-uV-xHxU;mI?jjh_@A5ovEvp[i<Zr7.b_G+PvIC|%?49&4#:.U+axL(*Bpe<34' );
define( 'SECURE_AUTH_SALT', '$a0%$A  EzC3nx:^5~,m1,ar/pI3elmU:RFo:^X_((SE%WB_F$kyc,WPq}Dp5Bb9' );
define( 'LOGGED_IN_SALT',   'bRF=p^=AK>kmD_wS^Puk~k)kmd9vMG+k,]rezhotL1k-GN&9+^hiCu;W;-DZGdzb' );
define( 'NONCE_SALT',       'TrbW8Qn>]KvF>BmT@t|8H,g=82#7;HrQd9BNPKT^Z_8SP4X*lZ*j<S4?/-<SW[@N' );

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
