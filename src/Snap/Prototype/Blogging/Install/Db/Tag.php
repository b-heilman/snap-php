<?php

namespace Snap\Prototype\Blogging\Install\Db;

class Tag 
	implements \Snap\Prototype\Installation\Lib\Definition {

	public function getTable(){
		return 'tagging';
	}
	public function getTableEngine(){
		return 'InnoDB';
	}
	public function getTableOptions(){
		return array('PRIMARY KEY (tag_id)');
	}
	
	public function getFields(){
		return array(
			'tag_id'      => array( 'type' => 'int unsigned', 'options' => array('AUTO_INCREMENT') ),
			'name'        => array( 'type' => 'varchar(30)' ),
			'description' => array( 'type' => 'text', 'nullable' => true )
		);
	}
	public function getPrepop(){
		return array();
	}
}