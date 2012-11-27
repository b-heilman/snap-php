<?php
/*
if ( !defined('GROUPS_DB') ){
	define('GROUPS_DB', SITE_DB);
}

if ( !defined('GROUPS_TABLE') ){
	define('GROUPS_TABLE', 'groups');
	define('GROUPS_ID', 'g_id');
	define('GROUPS_NAME', 'g_name');
}

if ( !defined('GROUPS_MEMBERS_TABLE') ){
	define('GROUPS_MEMBERS_TABLE', 'group_members');
	define('GROUPS_MEMBER_GROUP', 'g_id');
	define('GROUPS_MEMBER_USER', 'g_name');
}
*/
if ( !defined('WEBFS_DB') ){
	define('WEBFS_DB', SITE_DB);
}

if ( !defined('WEBFS_ACCESS') ){
	define('WEBFS_ACCESS', '/access.php');
}

if ( !defined('WEB_FILE_ROOT') ){
	define('WEB_FILE_ROOT', '/tmp');
}

if ( !defined('WEBFS_TABLE') ){
	define('WEBFS_TABLE', 'WEBFS');
	define('WEBFS_ID',    'f_id');
	define('WEBFS_NAME',  'f_mask');
	/*
	define('WEB_FILE_OWNER', 'owner_id');
	define('WEB_FILE_GROUP', 'group_id');
	define('WEB_FILE_PERMISSION', 'premissions');
	*/
}
	
global $prototype_info;

if ( !defined('INSTALL_HANDLER_SCHEMA') ){
	define('INSTALL_HANDLER_SCHEMA', WEBFS_DB);
}

if ( !isset($prototype_info) ){
	$prototype_info = array(
		'base' => array(
			'name'	=> 'WebFS'
		),
		'install' => array(
			'classes' => array(
				'webFS_file_proto'
			)
		),
		'management' => array(
			'Add File' => array(
				'forms' => array(
					'webFS_new_form_proto'
				)
			)
		)
	);
}