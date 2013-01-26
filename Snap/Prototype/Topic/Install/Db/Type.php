<?php

namespace Snap\Prototype\Topic\Install\Db;

class Type  
	implements \Snap\Prototype\Installation\Lib\Definition {
	
	public function getTable(){
		return TOPIC_TYPE_TABLE;
	}
	
	public function getTableEngine(){
		return 'InnoDB';
	}
	
	public function getTableOptions(){
		return array( 'PRIMARY KEY ('.TOPIC_TYPE_ID.')', 'UNIQUE ('.TOPIC_TYPE_NAME.')' );
	}
	
	public function getFields(){
		return array(
			TOPIC_TYPE_ID   => array( 'type' => 'int unsigned',  'options' => array('AUTO_INCREMENT') ),
			TOPIC_TYPE_NAME => array( 'type' => 'varchar(32)' ),
			'active'        => array( 'type' =>'bool',           'options' => array("default '1'") )
		);
	}
	
	public function getPrepop(){
		return array();
	}
}