<?php

namespace Snap\Prototype\Installation\Lib;

use 
	\Snap\Lib\Db;

class Prototype {

	public 
		$installRow,
		$installDir,
		$installable,
		$installs,
		$tables,
		$forms,
		$name;
		
	protected
		$db;
	
	public function __construct( $prototype ){
		// $prototype is just the prototype name
		if ( defined('CONTROL_DB') && CONTROL_DB != '' ){
			$class = CONTROL_DB_ADAPTER;
			$db = $this->db = new $class(CONTROL_DB);
		}else{
			$this->write('You need to set a CONTROL_DB to turn on install_prototyper functionality');
		}
			
		$this->name = $prototype; // name has \
		$this->dir = substr( str_replace('\\','/',$this->name), 1 ); // dir is all /
		
		$this->forms = stream_resolve_include_path( $this->dir.'/Install/forms.php' );
		$this->installDir = stream_resolve_include_path( $this->dir.'/Install/Db' );
		$this->installable = ($this->installDir != null);
		
		if ( $this->installable ){
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
			$this->installRow = null;
		}
		
		$this->tables = array();
		$this->installs = array();
		// load in the install information
		if ( file_exists($this->installDir) ){
			$dirs = scandir( $this->installDir );
			foreach( $dirs as $table ){
				if ( $table{0} != '.' ){
					if ( preg_match('/^[^.]*/', $table, $matches) ){
						$table = $matches[0];
						$class = $this->name.'\Install\Db\\'.$matches[0];
						
						$inst = new $class();
						if ( class_exists($class) && $inst instanceof \Snap\Prototype\Installation\Lib\Definition ){
							$this->tables[ $table ] = $inst;
							$this->installs[ $table ] = $db->tableExists( $inst->getTable() );
						}
					}
				}
			}
		}
		
		// loading in the forms info
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
	
	public function defineTable( \Snap\Prototype\Installation\Lib\Definition $dbDef ){
		$table = $dbDef->getTable();
			
		Db\Definition::addTable( $table, $dbDef->getTableOptions(), $dbDef->getTableEngine() );
		
		foreach( $dbDef->getFields() as $field => $fieldInfo ){
			Db\Definition::addTableField(
					$table,
					$field,
					$fieldInfo['type'],
					isset($fieldInfo['nullable']) ? $fieldInfo['nullable'] : false,
					isset($fieldInfo['options']) ? $fieldInfo['options'] : array()
			);
		}
		
		$prepop = $dbDef->getPrepop();
		
		if ( count($prepop) > 0 ){
			Db\Definition::addPrepop( $table, $prepop );
		}
	}

	public function define( array $tables ){
		// Load the definition classes and add them to the global definition
		foreach( $tables as $table ){
			if ( isset($this->tables[$table]) ){
				$this->defineTable( $this->tables[$table] );
			}
		}
	}
}