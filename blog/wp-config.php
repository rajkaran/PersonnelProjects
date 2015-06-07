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
define('DB_NAME', 'rajka381_wordpress');

/** MySQL database username */
define('DB_USER', 'rajka381_port');

/** MySQL database password */
define('DB_PASSWORD', 'ch@ng3m3');

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
define('AUTH_KEY',         'ZfTpBF-EG|W;i,3)$+qpJA!Ls)4^F=!(TyVbBJdC@#ib{VDDvh>m9PJ23b0#f*M?');
define('SECURE_AUTH_KEY',  '-Q|j?P78P.@i8w7b=|u02-iZ99hnJ86wq]N[{RQw1+bHHqf;L-z<0m-aEfh%2CGU');
define('LOGGED_IN_KEY',    ' &T3@9//}dhx{,7HQ`[c.P~`yJP+l~}b[+IA[o2.Z4>mSnf5xv0Fl*t}aW0I&>g=');
define('NONCE_KEY',        '<(Xhz=LbQcUQsSb!d$n*`p}a|esJ/>V8a:cQ##s1 Z;){%@=Z*J-N|KrH5?l-ov?');
define('AUTH_SALT',        '!+XHz,{+v+=O haw#f,Ts>6i[LOJ@qNb,d~5O&mj-([2ni#>IGGm?1$g>FyUm*6l');
define('SECURE_AUTH_SALT', '#<]CT6|o;b<>fQ?vDf|#StL%4;xKs{-YqN1]7| p1/0`vZT.Wl>urXM$A1BM&jXU');
define('LOGGED_IN_SALT',   '<Oq1QK/5-`bPu{.a%ze1A-Q|?%+~Q!p8A5bjbRK@$^r<E]J$]T0!w2%M3IyIB--p');
define('NONCE_SALT',       's(s51+)Edd2 ~Z4IaR;~S.TpR^Qojg0l369-o%LLYp@ekdT6M@iDN$G0-@bS.^GY');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
