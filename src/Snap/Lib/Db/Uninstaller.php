<?php

namespace Snap\Lib\Db;

class Uninstaller{

	static private 
		$count = null,
		$connections,
		$builds,
		$triggers,
		$instance,
		$db;

	public static function init(){
		self::$connections = array();
		self::$builds = array();
		self::$triggers = array();
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
		error_log( 'running uninstaller : '.count(self::$builds) );
		while( count(self::$builds) > 0 ){
			$matched = false;
			
			foreach( self::$builds as $table => $sql ){
				if ( empty(self::$connections[$table]) ){
					$matched = true;

					if ( !$handler->multi( $sql ) ){
						return false;
					}

					if ( trim(self::$triggers[$table]) != '' ){
						if ( !$handler->multi( self::$triggers[$table] ) ){
							return false;
						}
					}

					unset( self::$builds[$table] );
					unset( self::$connections[$table] );
					unset( self::$triggers[$table] );

					// remove if it's a connection
					foreach( self::$connections as $key => &$list ){
						if ( is_array($list) ){
							$pos = array_search($table, $list);
							if ( $pos !== false ){
								array_splice($list, $pos, 1);
							}
						}
					}
				}
			}

			if ( !$matched ){
				// So if nothing was matched, and this nothing installed, then we
				// need to do a last ditch effort to get this installed.
				// Perhaps the table connects to one outside this scope.
				foreach( self::$builds as $table => $sql ){
					if ( !$handler->multi( $sql ) ){
						return false;
					}

					if ( isset(self::$triggers[$table]) && trim(self::$triggers[$table]) != '' ){
						if ( !$handler->multi( self::$triggers[$table] ) ){
							return false;
						}
					}

					unset( self::$builds[$table] );
				}
			}
		}

		self::init();
		
		return true;
	}

	static public function registerAction($db, $table, $sql, $triggers ='', $connections = array()){
		if ( self::$instance == null ){
			self::$instance = new Uninstaller($db);
		}

		// I need to flip the connections...
		foreach ( $connections as $connection ){
			if ( !isset(self::$connections[$connection]) ){
				self::$connections[$connection] = array();
			}
			self::$connections[$connection][] = $table;
		}

		self::$builds[$table] = $sql;
		self::$triggers[$table] = $triggers;
	}
}