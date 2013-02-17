<?php

namespace Snap\Prototype\Installation\Lib;

use 
	\Snap\Lib\Db;

class Prototype {

	static protected 
		$prototypes = null;
	
	public 
		$installRow,
		$installDir,
		$installable,
		$installed,
		$forms,
		$name;
		
	public function __construct( $prototype ){
		if ( self::$prototypes == null ){
			$this->init();
		}
		
		// $prototype is just the prototype name
		
		$this->name = $prototype; // name has \
		$this->dir = substr( str_replace('\\','/',$this->name), 1 ); // dir is all /
		
		$this->forms = stream_resolve_include_path( $this->dir.'/Install/forms.php' );
		$this->installDir = stream_resolve_include_path( $this->dir.'/Install/Db' );
		$this->installable = ($this->installDir != null);
		
		if ( $this->installable ){
			$this->installed = isset( self::$prototypes[$prototype] );
			
			$row = stream_resolve_include_path( $this->dir.'/Node/Install/Row.php' );
			$class = $row ? $this->name.'\Node\Install\Row' : '\Snap\Prototype\Installation\Node\Install\Row';
			$p = $this;
			
			// TODO : this is a bit hacky, need to figure out something better
			// was for a bug, where the user row is getting defined early and thus only prototype feed consumed
			$this->installRow = function () use ($class, $p)  {
					return new $class(array(
					'prototype'    => $p,
					'outputStream' => 'prototype_action'
				));
			};
		}else{
			$this->installed = true;
			$this->installRow = null;
		}
		
		if ( $this->forms ){
			$forms = null;
			
			if ( file_exists($this->forms) ){
				include( $this->forms );
				
				if ( $forms ){
					$this->forms = $forms;
				}
			}else{
				$this->forms = null;
			}
		}
	}
	
	public function define(){
		if ( file_exists($this->installDir) ){
			$dirs = scandir( $this->installDir );
			foreach( $dirs as $table ){
				if ( $table{0} != '.' ){
					if ( preg_match('/^[^.]*/', $table, $matches) ){
						// Load the definition classes and add them to the global definition
						$class = $this->name.'\Install\Db\\'.$matches[0];
						
						$db_def = new $class();
						
						if ( $db_def instanceof \Snap\Prototype\Installation\Lib\Definition ){
							$table = $db_def->getTable();
							
							Db\Definition::addTable( $table, $db_def->getTableOptions(), $db_def->getTableEngine() );
							
							foreach( $db_def->getFields() as $field => $fieldInfo ){
								Db\Definition::addTableField( 
									$table, 
									$field,
									$fieldInfo['type'],
									isset($fieldInfo['nullable']) ? $fieldInfo['nullable'] : false, 
									isset($fieldInfo['options']) ? $fieldInfo['options'] : array()
								);
							}
							
							$prepop = $db_def->getPrepop();
							
							if ( count($prepop) > 0 ){
								Db\Definition::addPrepop( $table, $prepop );
							}
							
						}else throw new Exception('\Install\Db\* classes need to implement Installation\Lib\Definition');
					}
				}
			}
		}
	}
	
	public function install( \Snap\Adapter\Db $db ){
		return $db->insert( PROTOTYPE_TABLE, array('name' => $this->name) );
	}
	
	public function uninstall( \Snap\Adapter\Db $db ){
		return $db->delete( PROTOTYPE_TABLE, array('name' => $this->name) );
	}
	
	protected function init(){
		if ( defined('CONTROL_DB') && CONTROL_DB != '' ){
			$class = CONTROL_DB_ADAPTER;
			$db = new $class(CONTROL_DB);
		
			if ( !$db->accessable() ){
				if ( !$db->generate() ){
					throw new \Exception( 'Could not generate control DB' );
				}else{
					$db->commit();
				}
			}
		
			if ( !static::isControlInstalled($db) ){
				self::installControl( $db );
			}
			
			$res = $db->select( PROTOTYPE_TABLE );

			self::$prototypes = $res ? $res->asIndex('name') : array();
		}else{
			$this->write('You need to set a CONTROL_DB to turn on install_prototyper functionality');
		}
	}
	
	public static function installControl( $db = null ){
		if ( $db == null ){
			$class = CONTROL_DB_ADAPTER;
			$db = new $class(CONTROL_DB);
		}
		
		
		try {
			new \Snap\Prototype\Installation\Install\Db\Prototype();
				
			\Snap\Lib\Db\Definition::install( $db, false, true );
		}catch( Exceptions $ex ){
			throw new \Exception( 'Could not install base tables' );
		}
	}
	
	public static function isControlInstalled( $db = null ){
		if ( $db == null ){
			$class = CONTROL_DB_ADAPTER;
			$db = new $class(CONTROL_DB);
		}
		
		return $db->tableExists(PROTOTYPE_TABLE);
	}
}