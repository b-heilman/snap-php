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

	static public function run( $handler ){
		$handler->disableValidation();

		if( count(self::$tables) > 0 ){
			$matched = false;  //TODO when I can pull back info about already installed prototypes

			foreach( self::$tables as $table => $def ){
				if ( empty(self::$connections[$table]) ){
					$matched = true;

					if ( $handler->tableExists( $table ) ){
						//TODO instead of dropping the table, can we upgrade the table
						$handler->tableDrop( $table );
					}

					if ( !$handler->multi( $def->__toString() ) ){
						return false;
					}
					
					$trigger = $def->getTriggersSQL();
					if ( trim($trigger) != '' ){
						if ( !$handler->multi( $trigger ) ){
							return false;
						}
					}

					$def->runPostInstall( $handler );

					unset( self::$connections[$table] );

					// remove if it's a connection
					foreach( self::$connections as $key => &$list ){
						$pos = array_search($table, $list);
						if ( $pos !== false ){
							array_splice($list, $pos, 1);
						}
					}
				}
			}

			if ( $matched ){
				// TODO : I need to change this.  Right now I just run if something has been installed
				//	to try cleaning up a mess.  When I can poll for already installed prototypes I can
				// get rid of this
				foreach( self::$tables as $table => $def ){
					if ( $handler->tableExists( $table ) )
						$handler->tableDrop( $table );

					if ( !$handler->multi( $def->__toString() ) ){
						return false;
					}

					$trigger = $def->getTriggersSQL();
					if ( trim($trigger) != '' ){
						if ( !$handler->multi( $trigger ) ){
							return false;
						}
					}

					$def->runPostInstall( $handler );
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

		$tables = $table->getParentTables();
		$c = count($tables);

		for( $i = 0; $i < $c; ++$i ){
			if ( isset($tables[$i]) && $db->tableExists( $tables[$i] ) ){
				array_splice( $tables, $i );
				$i--;
				$c--;
			}
		}

		self::$connections[$tableName] = $tables;
		self::$tables[$tableName] = $table;
	}
}
