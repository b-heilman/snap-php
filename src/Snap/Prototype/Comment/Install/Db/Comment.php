<?php

namespace Snap\Prototype\Comment\Install\Db;

use \Snap\Lib\Db\Definition;

class Comment 
	implements \Snap\Prototype\Installation\Lib\Definition {
	
	public function getTable(){
		return COMMENT_TABLE;
	}
	
	public function getTableEngine(){
		return 'InnoDB';
	}
	
	public function getTableOptions(){
		return array('PRIMARY KEY ('.COMMENT_ID.')');
	}
	
	public function getFields(){
		return array(
			COMMENT_ID        => array( 'type' => 'int unsigned', 'options' => array('AUTO_INCREMENT') ),
			COMMENT_THREAD_ID => array( 'type' => 'int unsigned' ),
			COMMENT_USER      => array( 'type' => 'int unsigned' ),
			'content'         => array( 'type' => 'text' ),
			'creation_date'   => array( 'type' => 'timestamp',    'options' => array("DEFAULT CURRENT_TIMESTAMP") ),
			'active'          => array( 'type' => 'bool',         'options' => array("default '1'") ),
			COMMENT_PARENT    => array( 'type' => 'int unsigned')
		);
	}
	
	public function getPrepop(){
		return array();
	}
}	
	/* TODO : drop support, this info is maintained in the software level
		Definition::addTableRelation(COMMENT_TABLE, COMMENT_USER, 
			USER_DB.'.'.USER_TABLE, USER_ID, 'CASCADE', 'RESTRICT');
			
		Definition::addTableRelation(COMMENT_TABLE, COMMENT_THREAD_ID, 
			COMMENT_THREAD_TABLE, COMMENT_THREAD_ID, 'CASCADE', 'RESTRICT');
			
		Definition::addTableRelation(COMMENT_TABLE, COMMENT_PARENT, 
			COMMENT_TABLE, COMMENT_ID, 'CASCADE', 'CASCADE');
	*/
