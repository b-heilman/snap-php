<?php

namespace Snap\Prototype\User\Install\Db;

class User 
	implements \Snap\Prototype\Installation\Lib\Definition {
	
	public function getTable(){
		return 'users';
	}
	
	public function getTableEngine(){
		return 'InnoDB';
	}
	
	public function getTableOptions(){
		$options = array(
				'engine' => 'InnoDB',
				'PRIMARY KEY ( id )',
				'UNIQUE( login )',
				'UNIQUE( display )'
		);
		
		return $options;
	}
	
	public function getFields(){
		return array(
			'id'           => array( 'type' => 'int unsigned', 'options' => array('AUTO_INCREMENT') ),
			'login'        => array( 'type' => 'varchar(32)' ),
			'display'      => array( 'type' => 'varchar(32)' ),
			'password'     => array( 'type' => 'varchar(64)' ),
			'status'       => array( 'type' => "ENUM('CREATED', 'ACTIVE', 'INACTIVE')" ),
			'statusDate'   => array( 'type' => 'datetime' ),
			'creationDate' => array( 'type' => 'timestamp' ),
			'isAdmin'      => array( 'type' =>'bool' )
		);
	}
	
	public function getPrepop(){
		return array();
	}
}