<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'truckschoolwebsite' );
define('FS_METHOD', 'direct');

define('CONCATENATE_SCRIPTS', false );
/** MySQL database username */
define( 'DB_USER', 'admin' );

/** MySQL database password */
define( 'DB_PASSWORD', 'admin@123' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'lL0p>1hB~>1M!kDZ3BBM: ~3,BJ=Mp,]~DN#&0sup5]8|l1bEIZga;yU,0edU?C!' );
define( 'SECURE_AUTH_KEY',   'B. : A!^_qvg+[M[E14a  IK7hThs#M]8[t;nJb9q$,Td&13cElqCBDCtXPk&5m~' );
define( 'LOGGED_IN_KEY',     'HAL%p[&BW`2lyJu,mXc}BR7` :y6lwx6$Q9nc%w%zi8tL4#uwe1W1P%?=Qf}3A^R' );
define( 'NONCE_KEY',         '[Kvs9|w+L:>_t@/oW]Q:$^PGS=zb)MqG/rY>l:9/s+h1Kj+OlYk:zQpQHomK2$:-' );
define( 'AUTH_SALT',         '2$I|jf20LiyG2o/!m8S+t.v_/t!8@m9(epGD%d8mfZ#fr4l}i0-cObrf9]AnB.61' );
define( 'SECURE_AUTH_SALT',  'f8rni?7]&ezLhIf?c>zQ&s|- 1PvYW[Y,WpW~~&@)B&HKf;E^BVo?Sq:^)8s;:t9' );
define( 'LOGGED_IN_SALT',    'q>GE$M]9#V6ThDT_Y4=0_B,Gai<C=?* #TFn{]GQxTpDTegcQ9#A,PC<.?4w@1cj' );
define( 'NONCE_SALT',        'E;yHb;>AhG~k/x SO^Uzns2L~US+b({NRaWKW~h[0^M(a(*4^Yo#>fx Wtn.klf#' );
define( 'WP_CACHE_KEY_SALT', '13xw%y|9Pk_GkP;VBo6[%DivZ=aBhxi(/u[Egz0}C1T}DViX%4@$)cT_exMeH#H)' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';





/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
