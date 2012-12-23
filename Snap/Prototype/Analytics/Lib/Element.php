<?php

namespace Snap\Prototype\Analytics\Lib;

class Element extends \Snap\Lib\Db\Element 
	implements \Snap\Lib\Db\Feed {
	
	static protected 
		$has_run = false,
		$id_field = ANALYTICS_ID,
		$name_field = ANALYTICS_ID,
		$table = ANALYTICS_TABLE,
		$db = null;

	static protected function loadDB(){
		if ( self::$db == null ){
			self::$db = new \Snap\Adapter\Db\Mysql( ANALYTICS_DB );
		}
	}
	
	public function __construct($data = array() ){
		// TODO : This is a horrible design, but I just wanna get it done
		parent::__construct($data);
	}
	
	public static function getAdapter(){
		self::loadDB();
		
		return self::$db;
	}
	
	public function getContentQuery(){
		$this->load();
		
		return new \Snap\Lib\Db\Query( array(
			'select' => new \Snap\Lib\Db\Query\Select( array( 
				linking_linkanator_INDEX       => ANALYTICS_ID,
				linking_linkanator_SHORT_TITLE => ANALYTICS_IP,
				linking_linkanator_LONG_TITLE  => ANALYTICS_IP,
				linking_linkanator_TIME        => 'creation_date',
				linking_linkanator_CONTENT     => ANALYTICS_USER
			) ),
			'from' => ANALYTICS_TABLE
		) );
	}
	
	public function getPrimaryField(){
		return ANALYTICS_TABLE.'.'.ANALYTICS_ID;
	}
}