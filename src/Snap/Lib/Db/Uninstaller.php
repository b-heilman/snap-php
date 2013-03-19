<?php

namespace Snap\Lib\Db;

class Uninstaller{

	static private 
		$instance,
		$tables,
		$count = null,
		$db = null;

	public static function init(){
		self::$tables = array();
		self::$instance = null;
		self::$count = 0;
	}

	public function __construct($db){
		// Ideally, this function only gets called once
		if ( is_null(self::$count) ){
			self::init();
		}
		self::$count++;
	
		self::$db = $db;
	}

	static public function run( $handler = null ){
		if ( $handler == null )
			$handler = self::$db;

		$handler->disableValidation();
		
		foreach( self::$tables as $table => $def ){
			if ( $handler->tableExists( $table ) ){
				if ( !$handler->multi( $def->uninstall() ) ){
					return false;
				}
			}
		}

		self::init();
		
		return true;
	}

	static public function registerAction( \Snap\Adapter\Db $db, $tableName, \Snap\Lib\Db\Table\Definition $table ){
		if ( self::$instance == null ){
			self::$instance = new Uninstaller($db);
		}

		self::$tables[$tableName] = $table;
	}
}