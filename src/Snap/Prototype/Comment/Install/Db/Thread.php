<?php

namespace Snap\Prototype\Comment\Install\Db;

class Thread 
	implements \Snap\Prototype\Installation\Lib\Definition {
	
	public function getTable(){
		return COMMENT_THREAD_TABLE;
	}
	
	public function getTableEngine(){
		return 'InnoDB';
	}
	
	public function getTableOptions(){
		return array('PRIMARY KEY ('.COMMENT_THREAD_ID.')');
	}
	
	public function getFields(){
		return array(
			COMMENT_THREAD_ID   => array( 'type' => 'int unsigned', 'options' => array('AUTO_INCREMENT') ),
			COMMENT_THREAD_USER => array( 'type' => 'int unsigned' ),
			'creation_date'     => array( 'type' => 'timestamp',    'options' => array("DEFAULT CURRENT_TIMESTAMP") ),
			'active'            => array( 'type' => 'bool',         'options' => array("default '1'") )
		);
	}
	
	public function getPrepop(){
		return array();
	}
}