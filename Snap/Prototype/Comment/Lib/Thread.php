<?php

namespace Snap\Prototype\Comment\Lib;

class Thread extends \Snap\Lib\Db\Element {

	static protected 
		$id_field = COMMENT_THREAD_ID,
		$name_field = COMMENT_THREAD_ID,
		$table = COMMENT_THREAD_TABLE,
		$db = null;

	static protected function loadDB(){
		if ( self::$db == null ){
			self::$db = new \Snap\Adapter\Db\Mysql( COMMENT_DB );
		}
	}
	
	static public function create( $data = null ){
		self::callStatic('loadDB');
		$db = self::pullStatic('db');
		
		$user = \Snap\Prototype\User\Lib\Current::getUser();
		
		$db->insert(COMMENT_THREAD_TABLE, array(
			COMMENT_THREAD_USER => $user->id()
		));
		
		return $db->insertedID();
	}
}
