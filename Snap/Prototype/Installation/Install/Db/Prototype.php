<?php

namespace Snap\Prototype\Installation\Install\Db;

class Prototype 
	implements \Snap\Prototype\Installation\Lib\Definition {
	
	public function getTable(){
		return PROTOTYPE_TABLE;
	}
	
	public function getTableEngine(){
		return 'InnoDB';
	}
	
	public function getTableOptions(){
		return array('PRIMARY KEY ( p_id )','UNIQUE (name)');
	}
	
	public function getFields(){
		return array(
			'p_id'      => array( 'type' => 'int unsigned', 'options' => array('AUTO_INCREMENT') ),
			'name'      => array( 'type' => 'varchar(128)' ),
			'installed' => array( 'type' => 'bool', 'options' => array('default 1') ) 
		);
	}
	
	public function getPrepop(){
		return array();
	}
}