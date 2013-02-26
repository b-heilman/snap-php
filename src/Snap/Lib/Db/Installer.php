<?php
// TODO : 	I really should be shot for this shitty code, but it works... I'll clean it up during the next
//			revision now that I see how I want it to work.

namespace Snap\Lib\Db;

class Installer {

	static private 
		$count = null,
		$connections,
		$tables,
		$instance,
		$db = null;

	public static function init(){
		self::$connections = array();
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

	
	static public function run( \Snap\Adapter\Db $handler ){
		$handler->disableValidation();

		if( count(self::$tables) > 0 ){
			$handler->autocommit( false );
			
			foreach( self::$tables as $table => $def ){
				/* @var $def \Snap\Lib\Db\Support */
				
				if ( $handler->tableExists( $table ) ){
					//TODO instead of dropping the table, can we upgrade the table?
					$handler->tableDrop( $table );
				}

				if ( !$handler->multi( $def->__toString() ) ){
					return false;
				}
			}

			$handler->autocommit( true );
			
			foreach( self::$tables as $table => $def ){
				$data = $def->getPrepop();
				
				$def->runPostInstall( $handler );
				
				foreach( $data as $d ){
					$handler->insert( $table, $d );
				}
			}
			
			self::$tables = array();
		}

		$handler->enableValidation();

		self::init();
		
		return true;
	}

	static public function registerAction( \Snap\Adapter\Db $db, $tableName, \Snap\Lib\Db\Table\Definition $table ){
		if ( self::$instance == null ){
			self::$instance = new Installer($db);
		}

		self::$tables[$tableName] = $table;
	}
}
