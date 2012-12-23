<?php

// CREATE USER 'user'@'localhost' IDENTIFIED BY 'password';
// GRANT SUPER ON * . * TO 'user'@'localhost'
// GRANT ALL PRIVILEGES ON schema.* TO 'user'@'localhost'

if ( !defined('SITE_DB') ){
	define( 'SITE_DB', 'default' );
}

if ( !defined('SITE_DB_ADAPTER') ){
	define( 'SITE_DB_ADAPTER',          '\Snap\Adapter\Db\Mysql' );
	define( 'SITE_DB_TABLE_DEFINITION', '\Snap\Lib\Db\Mysql\Table\Definition' );
	define( 'SITE_DB_TABLE_RELATION',   '\Snap\Lib\Db\Mysql\Table\Relation' );
	define( 'SITE_DB_TABLE_FIELD',      '\Snap\Lib\Db\Mysql\Table\Field' );
	define( 'SITE_DB_TABLE_TRIGGER',    '\Snap\Lib\Db\Mysql\Table\Trigger' );
}