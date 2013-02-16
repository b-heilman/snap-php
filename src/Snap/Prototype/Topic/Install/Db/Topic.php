<?php

namespace Snap\Prototype\Topic\Install\Db;

\Snap\Lib\Core\Bootstrap::includeConfig( 'Snap/Prototype/Comment/Install/config.php' );

class Topic  
	implements \Snap\Prototype\Installation\Lib\Definition {
	
	public function getTable(){
		return TOPIC_TABLE;
	}
	
	public function getTableEngine(){
		return 'InnoDB';
	}
	
	public function getTableOptions(){
		return array('PRIMARY KEY ('.TOPIC_ID.')','UNIQUE ('.TOPIC_TITLE.', '.TOPIC_TYPE_ID.')');
	}
	
	public function getFields(){
		return array(
			TOPIC_ID             => array( 'type' => 'int unsigned',   'options' => array('AUTO_INCREMENT') ),
			TOPIC_TITLE          => array( 'type' => 'varchar(64)' ),
			'content'            => array( 'type' => "text" ),
			TOPIC_TYPE_ID        => array( 'type' => 'int unsigned' ),
			TOPIC_COMMENT_THREAD => array( 'type' => 'int unsigned' ),
			'creation_date'      => array( 'type' =>'timestamp',       'options' => array("DEFAULT CURRENT_TIMESTAMP") ),
			'active'             => array( 'type' =>'bool',            'options' => array("default '1'") )
		);
	}
	
	public function getPrepop(){
		return array();
	}
}