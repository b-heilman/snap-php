<?php

namespace Snap\Prototype\WebFs\Install\Db;

use \Snap\Lib\Db\Definition;

class File 
	implements \Snap\Prototype\Installation\Lib\Definition {
	
	public function getTable(){
		return WEBFS_TABLE;
	}

	public function getTableEngine(){
		return 'InnoDB';
	}

	public function getTableOptions(){
		return array('PRIMARY KEY ('.WEBFS_ID.')','UNIQUE ('.WEBFS_NAME.')');
	}

	public function getFields(){
		return array(
			WEBFS_ID        => array( 'type' => 'int unsigned',  'options' => array('AUTO_INCREMENT') ),
			WEBFS_NAME      => array( 'type' => 'varchar(64)' ),
			'path'          => array( 'type' => 'varchar(128)' ),
			'original_name' => array( 'type' => 'text' ),
			'extension'     => array( 'type' => 'varchar(8)' ),
			'creation_date' => array( 'type' => 'timestamp', 'options' => array("DEFAULT CURRENT_TIMESTAMP") )
		);
	}

	public function getPrepop(){
		return array();
	}
}