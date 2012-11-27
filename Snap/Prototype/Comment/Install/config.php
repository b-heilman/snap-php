<?php

if ( !defined('COMMENT_DB') ){
	define( 'COMMENT_DB', SITE_DB );
}

if ( !defined('COMMENT_THREAD_TABLE') ){
	define('COMMENT_THREAD_TABLE', 'comment_threads');
	define('COMMENT_THREAD_ID', 'ct_id');
	define('COMMENT_THREAD_USER', 'u_id');
}

if ( !defined('COMMENT_TABLE') ){
	define('COMMENT_TABLE', 'comments');
	define('COMMENT_ID', 'c_id');
	define('COMMENT_USER', 'u_id');
	define('COMMENT_PARENT', 'parent_c_id');
}
