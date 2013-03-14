<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'vengland');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'v}:%E@.zQhU:ajpJrcAF-9*Bv7*uwlT;kDxu#2#y&W8l^k%a-`T2$|zg),}N,NkB');
define('SECURE_AUTH_KEY',  'F9^6m7^6[bssC>{BmQnh#;Ek, X/igUV0LUxInD~L@lKkYj9jc7U%~}6y)Hx@Pz^');
define('LOGGED_IN_KEY',    'K}a/!e4+^!mY5v!juW>bDM{<V#1I=%FZe ``;4F0yh?hGjP|JzT,Un#evG[w16oW');
define('NONCE_KEY',        '%^cQ7F5tLKRTI$RQ}>_Cn4dVb%Yzy^##/BLS0 ~lBU)iqrdL&!iXXV1&O<(1J^4)');
define('AUTH_SALT',        '}XX#^Y3lMi+-@j>Frsg%8:yP vIPw4o^Dtla? Dm?: xrbpY`A4[woA^4XU~BD j');
define('SECURE_AUTH_SALT', 'y#{hD&F2-5]KL5#)!U7/)?8U[Wm/%4$gCgR^kB*6yZf?^^./>&BMZuYdhE!Ilux%');
define('LOGGED_IN_SALT',   'h(U7mG! )jIKzn/_EHdb>A,ViOEEbMw9f6}&yv/&~n?3>Ran dMAZt(`p&@}pO/L');
define('NONCE_SALT',       'H(**t0e<t} vAc{4%gU`~!j!A:!.?If 5B>x]e/B!#8ss v8JAc^$B^,>7j%>J#?');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 've_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);
error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
