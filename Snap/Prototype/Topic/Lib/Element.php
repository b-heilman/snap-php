<?php

namespace Snap\Prototype\Topic\Lib;

class Element extends \Snap\Lib\Db\Element 
	implements \Snap\Lib\Db\Feed {
	
	static protected 
		$id_field = TOPIC_ID,
		$name_field = TOPIC_TITLE,
		$table = TOPIC_TABLE,
		$db = null;

	public static function getAdapter(){
		self::loadDB();
		
		return self::$db;
	}
	
	public function getContentQuery(){
		$this->load();
		
		return new \Snap\Lib\Db\Query( array(
			'select' => new \Snap\Lib\Db\Select( array( 
				COMMENT_ID,
				paging_paginator_SHORT_TITLE => TOPIC_TITLE,
				paging_paginator_LONG_TITLE  => TOPIC_TITLE,
				paging_paginator_TIME => 'creation_date',
				paging_paginator_CONTENT => 'content'
			) ),
			'from' => new \Snap\Lib\Db\From( array(TOPIC_TABLE) ),
			'where' => new \Snap\Lib\Db\Where( array(COMMENT_THREAD_ID => $this->info(TOPIC_COMMENT_THREAD)) )
		) );
	}
	
	public function getPrimaryField(){
		return COMMENT_ID;
	}
	
	public function query( \Snap\Lib\Db\Query $query ){
		$db = self::$db;
		
		return $db->query( $query );
	}
	
	static protected function loadDB(){
		if ( self::$db == null ){
			self::$db = new \Snap\Adapter\Db\Mysql( TOPIC_DB );
		}
	}
	
	static public function create( $data ){
		if ( !isset($data[TOPIC_COMMENT_THREAD]) ){
			$data[TOPIC_COMMENT_THREAD] = \Snap\Prototype\Comment\Lib\Element::createThread();
		}
		
		return parent::create($data);
	}
}