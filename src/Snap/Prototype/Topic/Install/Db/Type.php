<?php

namespace Snap\Prototype\Topic\Install\Db;

class Type  
	implements \Snap\Prototype\Installation\Lib\Definition {
	
	public function getTable(){
		return 'topic_types';
	}
	
	public function getTableEngine(){
		return 'InnoDB';
	}
	
	public function getTableOptions(){
		return array( 'PRIMARY KEY (id)', 'UNIQUE (name)' );
	}
	
	public function getFields(){
		return array(
			'id'      => array( 'type' => 'int unsigned',  'options' => array('AUTO_INCREMENT') ),
			'name'    => array( 'type' => 'varchar(32)' ),
			'active'  => array( 'type' =>'bool' )
		);
	}
	
	public function getPrepop(){
		return array();
	}
}