<?php

// BEGIN iThemes Security - Do not modify or remove this line
// iThemes Security Config Details: 2
define( 'DISALLOW_FILE_EDIT', true ); // Disable File Editor - Security > Settings > WordPress Tweaks > File Editor
// END iThemes Security - Do not modify or remove this line

define( 'ITSEC_ENCRYPTION_KEY', 'T3g2XmJzfSxZeDBuVy9qQi9LLFExPyFyMj1gWV56cVAuXXlMYS47LD5ONE1PbHstYyNdUkYxdmZhUjQyMjFmUQ==' );

require_once (__DIR__ . '/vendor/autoload.php');

function string_to_boolean($str) {
    if($str === true ||$str == 'true' || $str === 1) return true;
	return false;
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->ifPresent('WP_DEBUG')->isBoolean();
// $dotenv->ifPresent('WP_DEBUG_DISPLAY')->isBoolean();
$dotenv->load();

define('DB_NAME', $_ENV['DB_NAME']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASSWORD', $_ENV['DB_PASSWORD']);
define('DB_HOST', $_ENV['DB_HOST']);

define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

define('WP_HOME', $_ENV['WP_HOME']);
define('WP_SITEURL', $_ENV['WP_SITEURL']);

define('DISABLE_WP_CRON', string_to_boolean($_ENV['DISABLE_WP_CRON']) ?? true);
define('AUTOMATIC_UPDATER_DISABLED', string_to_boolean($_ENV['AUTOMATIC_UPDATER_DISABLED']) ?? true);
define('DISALLOW_FILE_MODS', string_to_boolean($_ENV['DISALLOW_FILE_MODS']) ?? true);

define('AUTH_KEY',         'P:0NiU/uj0K&zbLI[%TmWZ:ir08Ar!^F`LV5CV#g-@dUMF}n}&Iwo?Z@}_8;yvxH');
define('SECURE_AUTH_KEY',  '9.rEnM^%XS Pyl7=g+{Hh!!aCKDHcnfv_2I0e/.-z!b*:i!8P*e~p+rI9(s/v-jy');
define('LOGGED_IN_KEY',    'hvt)<N>Ar70Xm2?S>V0)!RnB?+Z.CO~ei*4I3rg*/WAF(./JVBV9#!^_]<+c6g`{');
define('NONCE_KEY',        '6(Xq<c>Cav}&^`-|(`aMOwv@-RbMA|D*xJj*vHHB.^eUY%rt>;#gDfQjt-vd<T~X');
define('AUTH_SALT',        '3DGPEbT|]_<rx_|Ql}Gg57u|#<:i;d1zqtvspGO.R9Hfb;|]z(EsQ6a+KYh>{FA ');
define('SECURE_AUTH_SALT', 'Q</~ktc5K,jdtIUKW1W&|pb+`:GPkB5?{Gfy#!e#-VTW|k5hc&s+:_nm4pq&2f9Y');
define('LOGGED_IN_SALT',   ',5z-MPx]/*2}Q~@6oe_KM+~Hc3k4G{p-P~{L^n1q<x%Zal?!a(g3{jY>4H(N$7j:');
define('NONCE_SALT',       '>m_yE}84Lm({T1}AD}[LFsTi#p/c1q|X+$DS7N1W}hFj$y!YYCE+m-LIji{d>lg}');

$table_prefix = 'sp5465_';

define('WP_DEBUG', string_to_boolean($_ENV['WP_DEBUG']) );
define('WP_DEBUG_DISPLAY', string_to_boolean($_ENV['WP_DEBUG_DISPLAY']) );

define('FS_METHOD', $_ENV['FS_METHOD']);

if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
