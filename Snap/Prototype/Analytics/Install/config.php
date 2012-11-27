<?php
@include_once('prototypes/analytics/my_config.php');

if ( !defined('ANALYTICS_DB') ){
	define('ANALYTICS_DB', SITE_DB);
}

if ( !defined('ANALYTICS_TABLE') ){
	define('ANALYTICS_TABLE', 'analytics');
	define('ANALYTICS_ID', 'a_id');
	define('ANALYTICS_USER', 'u_id');
	define('ANALYTICS_IP', 'ip');
	define('ANALYTICS_BROWSER', 'browser');
	define('ANALYTICS_REFERER', 'referer');
	define('ANALYTICS_URL', 'url');
	define('ANALYTICS_NOTE', 'note');
}

if ( !defined('ANALYTICS_LOG_TABLE') ){
	define('ANALYTICS_LOG_TABLE', 'analytic_logs');
	define('ANALYTICS_LOG_ID', 'al_id');
}
