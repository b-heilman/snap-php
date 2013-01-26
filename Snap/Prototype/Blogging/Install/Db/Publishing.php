<?php

namespace Snap\Prototype\Blogging\Install\Db;

class Publishing 
	implements \Snap\Prototype\Installation\Lib\Definition {

	public function getTable(){
		return 'publishing';
	}
	public function getTableEngine(){
		return 'InnoDB';
	}
	public function getTableOptions(){
		return array('PRIMARY KEY (publishing_status)');
	}
	
	public function getFields(){
		return array(
			'publishing_status' => array( 'type' => 'int unsigned', 'options' => array('AUTO_INCREMENT') ),
			'code'              => array( 'type' => 'varchar(4)' ),
			'description'       => array( 'type' => 'varchar(30)' )
		);
	}
	public function getPrepop(){
		return array(
			array( 'publishing_status' => 1, 'code' => 'INIT', 'description' => 'Created but not published' ),
			array( 'publishing_status' => 2, 'code' => 'LIVE', 'description' => 'The blog has been published' ),
			array( 'publishing_status' => 3, 'code' => 'DEAD', 'description' => 'The blog has been removed' )
		);
	}
}