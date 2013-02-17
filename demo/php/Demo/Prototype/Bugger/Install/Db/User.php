<?php

namespace Demo\Prototype\Bugger\Install\Db;

class User 
	implements \Snap\Prototype\Installation\Lib\Definition {
	
	public function getTable(){
		return 'd_user';
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
			'id'      => array( 'type' => 'int unsigned', 'options' => array('AUTO_INCREMENT') ),
			'name'    => array( 'type' => 'varchar(32)' )
		);
	}
	
	public function getPrepop(){
		return array();
	}
}