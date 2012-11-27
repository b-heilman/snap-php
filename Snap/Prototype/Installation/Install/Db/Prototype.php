<?php

namespace Snap\Prototype\Installation\Install\Db;

use \Snap\Lib\Db\Defintion;

class Prototype {
	public function __construct(){
		Defintion::addTable( PROTOTYPE_TABLE, array('PRIMARY KEY ( p_id )','UNIQUE (name)'), 'InnoDB' );
		
		Defintion::addTableField( PROTOTYPE_TABLE, 'p_id', 'int unsigned', false, array('AUTO_INCREMENT') );
		Defintion::addTableField( PROTOTYPE_TABLE, 'name', 'varchar(32)', false );
		Defintion::addTableField( PROTOTYPE_TABLE, 'installed', 'bool', false, array("default '1'") );
	}
}