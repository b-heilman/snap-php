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
			'user_id'      => array( 'type' => 'int unsigned' ),
			'content'      => array( 'type' => 'text' ),
			'thread_id'    => array( 'type' => 'int unsigned' ),
			'parent_id'    => array( 'type' => 'int unsigned', 'nullable' => true ),
			'creationDate' => array( 'type' => 'timestamp' ),
			'active'       => array( 'type' => 'bool' )
		);
	}
	
	public function getPrepop(){
		return array();
	}
}
