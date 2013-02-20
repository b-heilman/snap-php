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
			'creationDate' => array( 'type' => 'timestamp',    'options' => array("DEFAULT CURRENT_TIMESTAMP") ),
			'active'       => array( 'type' => 'bool',         'options' => array("default '1'") )
		);
	}
	
	public function getPrepop(){
		return array();
	}
}

/* TODO : drop support, this info is maintained in the software level
	Definition::addTableRelation( COMMENT_THREAD_TABLE, COMMENT_THREAD_USER, USER_DB.'.'.USER_TABLE, USER_ID, 'CASCADE', 'RESTRICT' );
*/