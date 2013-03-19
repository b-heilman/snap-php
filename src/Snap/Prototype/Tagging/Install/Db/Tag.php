<?php

namespace Snap\Prototype\Tagging\Install\Db;

class Tag  
	implements \Snap\Prototype\Installation\Lib\Definition {
	
	public function getTable(){
		return 'tags';
	}
	
	public function getTableEngine(){
		return 'InnoDB';
	}
	
	public function getTableOptions(){
		return array( 'PRIMARY KEY (id)', 'UNIQUE (name)' );
	}
	
	public function getFields(){
		return array(
			'id'   => array( 'type' => 'int unsigned',  'options' => array('AUTO_INCREMENT') ),
			'name' => array( 'type' => 'varchar(63)' )
		);
	}
	
	public function getPrepop(){
		return array();
	}
}