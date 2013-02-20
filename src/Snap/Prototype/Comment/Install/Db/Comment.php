<?php

namespace Snap\Prototype\Comment\Install\Db;

use \Snap\Lib\Db\Definition;

class Comment 
	implements \Snap\Prototype\Installation\Lib\Definition {
	
	public function getTable(){
		return 'comments';
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
			'user'         => array( 'type' => 'int unsigned' ),
			'content'      => array( 'type' => 'text' ),
			'thread_id'    => array( 'type' => 'int unsigned' ),
			'parent_id'    => array( 'type' => 'int unsigned' ),
			'active'       => array( 'type' => 'bool' ),
			'creationDate' => array( 'type' => 'timestamp' )
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
