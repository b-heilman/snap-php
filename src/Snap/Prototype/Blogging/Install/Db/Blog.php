<?php

namespace Snap\Prototype\Blogging\Install\Db;

class Blog extends \Snap\Prototype\Topic\Install\Db\Topic {
	
	public function getTable(){
		return 'blogs';
	}
	
	public function getFields(){
		return parent::getFields() + array(
			'content'      => array( 'type' => 'text' )
		);
	}
}