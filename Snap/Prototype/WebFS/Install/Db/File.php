<?php

namespace Snap\Prototype\WebFs\Install\Db;

use \Snap\Lib\Db\Definition;

class File {
	public function __construct(){
		Definition::addTable( WEBFS_TABLE, array('PRIMARY KEY ('.WEBFS_ID.')','UNIQUE ('.WEBFS_NAME.')'), 'InnoDB' );
		
		Definition::addTableField( WEBFS_TABLE, WEBFS_ID,       'int unsigned', false, array('AUTO_INCREMENT'));
		Definition::addTableField( WEBFS_TABLE, WEBFS_NAME,     'varchar(64)',  false );
		Definition::addTableField( WEBFS_TABLE, 'path',         'varchar(128)', false );
		Definition::addTableField( WEBFS_TABLE, 'original_name', 'text',        false );
		Definition::addTableField( WEBFS_TABLE, 'extension',     'varchar(8)',  false );
		Definition::addTableField( WEBFS_TABLE, 'creation_date', 'timestamp',   false, array("DEFAULT CURRENT_TIMESTAMP") );
	}
}