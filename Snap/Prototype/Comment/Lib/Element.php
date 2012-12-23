<?php

namespace Snap\Prototype\Comment\Lib;

class Element extends \Snap\Lib\Db\Element {

	static protected 
		$id_field = COMMENT_ID,
		$name_field = COMMENT_ID,
		$table = COMMENT_TABLE,
		$db = null;
	
	static protected function loadDB(){
		if ( self::$db == null ){
			self::$db = new \Snap\Adapter\Db\Mysql( COMMENT_DB );
		}
	}
	
	static public function createThread(){
		return \Snap\Prototype\Comment\Lib\Thread::create();
	}
}