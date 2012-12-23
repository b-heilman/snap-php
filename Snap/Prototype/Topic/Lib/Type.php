<?php

namespace Snap\Prototype\Topic\Lib;

class Type extends \Snap\Lib\Db\Element 
	implements \Snap\Lib\Db\Feed {
	
	static protected 
		$id_field = TOPIC_TYPE_ID,
		$name_field = TOPIC_TYPE_NAME,
		$table = TOPIC_TYPE_TABLE,
		$db = null;

	public static function getAdapter(){
		self::loadDB();
		
		return self::$db;
	}
	
	public function getContentQuery(){
		$this->load();
		
		return new \Snap\Lib\Db\Query( array(
			'select' => new \Snap\Lib\Db\Query\Select( array( 
				linking_linkanator_INDEX         => TOPIC_ID,
				linking_linkanator_SHORT_TITLE   => TOPIC_TITLE, 
				linking_linkanator_LONG_TITLE    => TOPIC_TITLE,
				linking_linkanator_TIME          => 'creation_date',
				linking_linkanator_CONTENT       => 'content'
			) ),
			'from' => TOPIC_TABLE,
			'where' => new \Snap\Lib\Db\Query\Were( array(TOPIC_TYPE_ID => $this->info(TOPIC_TYPE_ID)) )
		) );
	}
	
	public function getPrimaryField(){
		return TOPIC_TABLE.'.'.TOPIC_ID;
	}
	
	static protected function loadDB(){
		if ( self::$db == null ){
			self::$db = new \Snap\Adapter\Db\Mysql( TOPIC_DB );
		}
	}
}