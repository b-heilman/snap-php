<?php

namespace Snap\Prototype\Blogging\Install\Db;

\Snap\Lib\Core\Bootstrap::includeConfig( 'Snap/Prototype/Topic/Install/config.php' );

class Entry implements \Snap\Prototype\Installation\Lib\Definition {

	public function getTable(){
		return 'blogging';
	}
	public function getTableEngine(){
		return 'InnoDB';
	}
	public function getTableOptions(){
		return array('PRIMARY KEY (entry_id)','UNIQUE ('.TOPIC_ID.')');
	}
	
	public function getFields(){
		return array(
			'entry_id'          => array( 'type' => 'int unsigned', 'options' => array('AUTO_INCREMENT') ),
			TOPIC_ID            => array( 'type' => 'int unsigned' ),
			'summary'           => array( 'type' => 'text' ),
			'publishing_status' => array( 'type' => 'int unsigned')
		);
	}
	public function getPrepop(){
		return array();
	}
}