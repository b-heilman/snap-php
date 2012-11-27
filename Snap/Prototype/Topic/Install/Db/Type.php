<?php

namespace Snap\Prototype\Topics\Install\Db;

use \Snap\Lib\Db\Defintion;

class Type {
	public function __construct(){
		Definition::addTable( TOPIC_TYPE_TABLE, array( 'PRIMARY KEY ('.TOPIC_TYPE_ID.')','UNIQUE ('.TOPIC_TYPE_NAME.')' ), 'InnoDB' );
		
		Definition::addTableField( TOPIC_TYPE_TABLE, TOPIC_TYPE_ID, 'int unsigned', false, array('AUTO_INCREMENT') );
		Definition::addTableField( TOPIC_TYPE_TABLE, TOPIC_TYPE_NAME, 'varchar(32)', false );
		Definition::addTableField( TOPIC_TYPE_TABLE, 'active', 'bool', false, array("default '1'") );
	}
}