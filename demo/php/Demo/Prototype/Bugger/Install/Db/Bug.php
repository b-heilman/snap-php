<?php

namespace Demo\Prototype\Bugger\Install\Db;

class Bug 
	implements \Snap\Prototype\Installation\Lib\Definition {
	
	public function getTable(){
		return 'bugs';
	}
	
	public function getTableEngine(){
		return 'InnoDB';
	}
	
	public function getTableOptions(){
		$options = array(
			'PRIMARY KEY ( id )'
		);
		
		return $options;
	}
	
	public function getFields(){
		return array(
			'id'          => array( 'type' => 'int unsigned', 'options' => array('AUTO_INCREMENT') ),
			'description' => array( 'type' => 'text' ),
			'created'     => array( 'type' => 'datetime' ),
			'status'      => array( 'type' => 'varchar(32)' ),
			'reporter_id' => array( 'type' => 'int unsigned', 'nullable' => true ),
			'engineer_id' => array( 'type' => 'int unsigned', 'nullable' => true )
		);
	}
	
	public function getPrepop(){
		return array();
	}
}