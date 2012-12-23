<?php

if ( !defined('TOPIC_DB') ){
	define('TOPIC_DB', SITE_DB);
}

if ( !defined('TOPIC_TYPE_TABLE') ){
	define('TOPIC_TYPE_TABLE', 'topic_types');
	define('TOPIC_TYPE_ID', 'tt_id');
	define('TOPIC_TYPE_NAME', 'tt_name');
}

if ( !defined('TOPIC_TABLE') ){
	define('TOPIC_TABLE', 'topics');
	define('TOPIC_ID', 't_id');
	define('TOPIC_TITLE', 't_title');
	define('TOPIC_COMMENT_THREAD', 'ct_id');
}