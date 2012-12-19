<?php

if ( !defined('SITE_DB_ADAPTER') ){
	\Snap\Lib\Core\Bootstrap::includeConfig( 'Snap/Config/Db.php' );
}

//TODO : I need to clean up a lot of definitions in here, they aren't system global anymore

if ( !defined('ALIGN_WIDTH') ){
    define('ALIGN_WIDTH', '100');
}

if ( !defined('CONTROL_DB') ){ // TODO : kill this 
	define('CONTROL_DB_ADAPTER', 		SITE_DB_ADAPTER);
	define('CONTROL_DB', 				SITE_DB);
}

global $prototype_pathes;
if ( !isset($prototype_pathes) ){
	$prototype_pathes = 'prototypes';
}

if ( !defined('PROTOTYPE_TABLE') ){
	define('PROTOTYPE_TABLE', '_prototypes');
}

if ( !defined('KRYPT_KEY') ){
	define('KRYPT_KEY', 		'W3 @r3 P3Nn St@t3');
	define('KRYPT_HASH', 		'sha256');
}
/*---------
A full example:
define('AUTH_CLASS', 		'users_auth_proto');
define('AUTH_INIT_STATUS', 	'CREATED');
define('AUTH_POST_STATUS', 	'VALID');
define('AUTH_VALID_STATUS', '(CREATED|VALID)');
define('AUTH_STATUS', 		'status');
---------*/
if ( !defined('AUTH_FIELD') ){
	define('AUTH_CLASS', 		'\Snap\Prototype\User\Lib\Auth');
	define('AUTH_INIT_STATUS', 	'CREATED');
	define('AUTH_VALID_STATUS', '');
	define('AUTH_STATUS_FIELD', 'status');
}

if ( !defined('WEB_FILE_PATH') ){
	define('WEB_FILE_PATH', '/tmp/web');
}

if ( !defined('WS_ACCESS_POINT') ){
	define('WS_ACCESS_POINT', '/');           // Web Services
}

if ( !defined('DATEPICKER_DISPLAY_FORMAT') ){
	define('DATEPICKER_DISPLAY_FORMAT', 'yy-mm-dd');
}

if ( !defined('SESS_MODE') ){
	define('SESS_MODE', 	'php');  // php, db, cookie, file
}

if ( !defined('SESS_TABLE') ){
	define('SESS_TABLE', 	'_session');
	define('SESS_COOKIE',	true);
	define('SESS_LINKS',   	true);
	define('SESS_SECURITY', 3);
	define('SESS_TIMEOUT',	0);
}
