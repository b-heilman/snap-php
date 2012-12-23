<?php

namespace Snap\Prototype\Installation\Lib;

use \Snap\Lib\Core\Bootstrap;

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
		$this->name = $prototype;
		$this->forms = stream_resolve_include_path( $prototype.'/Install/Forms.php' );
		$this->installDir = stream_resolve_include_path( $prototype.'/Install/Db' );
		$this->installable = ($this->installDir != null);
		
		if ( $this->installable ){
			$this->installed = isset( self::$prototypes[$prototype] );
			
			$row = stream_resolve_include_path( $prototype.'/Node/Install/Row.php' );
			$class = $row ? $prototype.'\Node\Install\Row' : '\Snap\Prototype\Installation\Node\Install\Row';
			
			$this->installRow = new $class(array(
				'prototype'    => $this,
				'outputStream' => 'prototype_action'
			));
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
						// TODO : obviously this breaks with nested prototypes
						$class = $this->name.'\Install\Db\\'.$matches[0];
						error_log( $class );
						$db_def = new $class();
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