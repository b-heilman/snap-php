<?php

namespace Snap\Prototype\Blogging\Install\Db;

class TagEntry 
	implements \Snap\Prototype\Installation\Lib\Definition {

	public function getTable(){
		return 'tagging_entry';
	}
	
	public function getTableEngine(){
		return 'InnoDB';
	}
	
	public function getTableOptions(){
		return array('UNIQUE(tag_id, entry_id)');
	}
	
	public function getFields(){
		return array(
			'tag_id'      => array( 'type' => 'int unsigned' ),
			'entry_id'    => array( 'type' => 'int unsigned' )
		);
	}
	
	public function getPrepop(){
		return array();
	}
}