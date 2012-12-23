<?php

namespace Snap\Lib\Db;

\Snap\Lib\Core\Bootstrap::includeConfig('Snap/Config/Db.php');

class Definition {
	
	static protected 
		$tables = array();

	static public function addTable( $table, $options = array(), $engine = null ){
		$class = SITE_DB_TABLE_DEFINITION;

		self::$tables[$table] = new $class( $table, $options, $engine );
	}

	static public function addTableRelation($table, $field, $foreignTable, $foreignField,
								 $onUpdate = '', $onDelete = '', $options = array()){
		if ( !isset(self::$tables[$table]) ){
			throw new \Exception($table.' was not defined in a class, but relations was added.');
		}else{
			$class = SITE_DB_TABLE_RELATION;

			self::$tables[$table]->addRelation( new $class($field, $foreignTable, $foreignField,
															$onUpdate, $onDelete, $options) );
		}
	}

	static public function addTableField($table, $field, $type, $nullable = true, $options = array()){
		if ( !isset(self::$tables[$table]) ){
			throw new \Exception($table.' was not defined in a class, but field was added.');
		}else{
			$class = SITE_DB_TABLE_FIELD;

			self::$tables[$table]->addField( new $class($field, $type, $nullable, $options) );
		}
	}

	static public function addTableTrigger($table, $action, $when, $trigger, $options = array()){
		if ( !isset(self::$tables[$table]) ){
			throw new \Exception($table.' was not defined in a class, but field was added.');
		}else{
			$class = SITE_DB_TABLE_TRIGGER;

			self::$tables[$table]->addTrigger( new $class($table, $action, $when, $trigger, $options) );
		}
	}
	
	static public function addPostInstall( $table, $func ){
		if ( !isset(self::$tables[$table]) ){
			throw new \Exception($table.' was not defined in a class, but field was added.');
		}else{
			self::$tables[$table]->addPostInstall( $func );
		}
	}

	static public function install( \Snap\Adapter\Db $handler ){
		if ( !empty(self::$tables) ){
			foreach( self::$tables as $table => $def ){
				\Snap\Lib\Db\Installer::registerAction( $handler, $table, $def );
			}
			
			return \Snap\Lib\Db\Installer::run( $handler );
		} else return false;
	}

	static public function uninstall( \Snap\Adapter\Db $handler ){
		if ( !empty(self::$tables) ){
			foreach( self::$tables as $table => $def ){
				$def->setMode( \Snap\Lib\Db\Support::$UNINSTALL_MODE );
				\Snap\Lib\Db\Installer::registerAction( $handler, $table, $def );
			}
			
			return \Snap\Lib\Db\Uninstaller::run( $handler );
		} else return false;
	}
}