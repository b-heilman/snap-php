<?php

namespace Snap\Prototype\Installation\Install\Db;

use \Snap\Lib\Db;

class Prototype {
	public function __construct(){
		Db\Definition::addTable( PROTOTYPE_TABLE, array('PRIMARY KEY ( p_id )','UNIQUE (name)'), 'InnoDB' );
		
		Db\Definition::addTableField( PROTOTYPE_TABLE, 'p_id', 'int unsigned', false, array('AUTO_INCREMENT') );
		Db\Definition::addTableField( PROTOTYPE_TABLE, 'name', 'varchar(32)', false );
		Db\Definition::addTableField( PROTOTYPE_TABLE, 'installed', 'bool', false, array("default '1'") );
	}
}