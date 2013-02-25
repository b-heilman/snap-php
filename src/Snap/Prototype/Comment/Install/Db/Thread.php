<?php

namespace Snap\Prototype\Comment\Install\Db;

class Thread 
	implements \Snap\Prototype\Installation\Lib\Definition {
	
	public function getTable(){
		return 'comment_threads';
	}
	
	public function getTableEngine(){
		return 'InnoDB';
	}
	
	public function getTableOptions(){
		return array('PRIMARY KEY (id)');
	}
	
	public function getFields(){
		return array(
			'id'           => array( 'type' => 'int unsigned', 'options' => array('AUTO_INCREMENT') ),
			'user_id'      => array( 'type' => 'int unsigned' ),
			'creationDate' => array( 'type' => 'timestamp' ),
			'active'       => array( 'type' => 'bool' )
		);
	}
	
	public function getPrepop(){
		return array();
	}
}
