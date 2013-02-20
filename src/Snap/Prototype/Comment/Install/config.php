<?php

if ( !defined('COMMENT_DB') ){
	define( 'COMMENT_DB', SITE_DB );
}

if ( !defined('COMMENT_THREAD_TABLE') ){
	define('COMMENT_THREAD_TABLE', 'comment_threads');
	define('COMMENT_THREAD_ID',    'id');
	define('COMMENT_THREAD_USER',  'user');
}

if ( !defined('COMMENT_TABLE') ){
	define('COMMENT_TABLE',  'comments');
	define('COMMENT_ID',     'id');
	define('COMMENT_USER',   'user');
	define('COMMENT_PARENT', 'parentComment');
}
