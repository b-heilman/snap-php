<?php

namespace Snap\Prototype\Topic\Install\Db;

class Topic  
	implements \Snap\Prototype\Installation\Lib\Definition {
	
	public function getTable(){
		return 'topics';
	}
	
	public function getTableEngine(){
		return 'InnoDB';
	}
	
	public function getTableOptions(){
		return array('PRIMARY KEY (id)','UNIQUE (name, type_id)');
	}
	
	public function getFields(){
		return array(
			'id'           => array( 'type' => 'int unsigned', 'options' => array('AUTO_INCREMENT') ),
			'name'         => array( 'type' => 'varchar(64)' ),
			'type_id'      => array( 'type' => 'int unsigned' ),
			'thread_id'    => array( 'type' => 'int unsigned' ),
			'creationDate' => array( 'type' => 'timestamp' ),
			'active'       => array( 'type' => 'bool' )
		);
	}
	
	public function getPrepop(){
		return array();
	}
}