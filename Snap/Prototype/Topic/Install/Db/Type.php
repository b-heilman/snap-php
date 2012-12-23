<?php

namespace Snap\Prototype\Topic\Install\Db;

use \Snap\Lib\Db;

class Type {
	public function __construct(){
		Db\Definition::addTable( TOPIC_TYPE_TABLE, array( 'PRIMARY KEY ('.TOPIC_TYPE_ID.')',
			'UNIQUE ('.TOPIC_TYPE_NAME.')' ), 'InnoDB' );
		
		Db\Definition::addTableField( TOPIC_TYPE_TABLE, TOPIC_TYPE_ID, 'int unsigned', false, array('AUTO_INCREMENT') );
		Db\Definition::addTableField( TOPIC_TYPE_TABLE, TOPIC_TYPE_NAME, 'varchar(32)', false );
		Db\Definition::addTableField( TOPIC_TYPE_TABLE, 'active', 'bool', false, array("default '1'") );
	}
}