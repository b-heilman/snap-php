<?php

if ( !defined('USER_DB') ){
	define( 'USER_DB', SITE_DB );
}

if ( !defined('USER_TABLE') ){
	define('USER_TABLE', 		'users');
	define('USER_ID', 			'user_id');
	define('USER_PASSWORD', 	'password');
	define('USER_ADMIN', 		'admin');
	define('USER_CLASS', 		'\Snap\Prototype\User\Lib\Element');
	define('USER_LOGIN', 		'login');
	define('USER_DISPLAY', 		'display');
}

if ( !defined('USER_LOGIN_LABEL') ){
	define('USER_LOGIN_LABEL', 	'Login');
	define('USER_DISPLAY_LABEL', 'Display');
}

if ( !defined('USER_FB_ID') ){
	define('USER_FB_ID', false);
	define('USER_FB_SECRET', false);
	define('USER_FB_FIELD', false);
}
