<?php

namespace Snap\Prototype\Tagging\Install\Db;

class TopicTag  
	implements \Snap\Prototype\Installation\Lib\Definition {
	
	public function getTable(){
		return 'topic_tags';
	}
	
	public function getTableEngine(){
		return 'InnoDB';
	}
	
	public function getTableOptions(){
		return array( 'PRIMARY KEY (topic_id, tag_id)' );
	}
	
	public function getFields(){
		return array(
			'topic_id' => array( 'type' => 'int unsigned' ),
			'tag_id'   => array( 'type' => 'int unsigned' )
		);
	}
	
	public function getPrepop(){
		return array();
	}
}