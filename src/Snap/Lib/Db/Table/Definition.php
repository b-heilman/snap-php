<?php

namespace Snap\Lib\Db\Table;

use \Snap\Lib\Db\Support;

abstract class Definition extends Support {

	protected 
		$fields    = array(),
		$posts     = array(),
		$prepop    = array(),
		$name,
		$engine;

	public function __construct( $table, $options = array(), $engine = null){
		$this->name   = $table;
		$this->ops    = $options;
		$this->engine = $engine;
	}

	public function mergeTable( $table, $options, $engine ){
		if ( $this->name != $table ){
			throw new \Exception('table names must match');
		}
		
		if ( $this->engine != $engine ){
			throw new \Exception('table engines must match');
		}
		
		$this->ops = $options + $this->ops;
	}
	
	public function addField( Field $field ){
		$name = $field->getName();
		
		if ( !isset($this->fields[$name]) ){
			$this->fields[$name] = $field;
		}else{
			// TODO : some sort of error checking might be nice?
		}
	}
	
	public function addPostInstall ( $func ){
		$this->posts[] = $func;
	}

	public function addPrepop ( $info ){
		$this->prepop = $info;
	}
	
	// right now I just use this to install, so do it that way
	protected function getFields( $glue = ", \n'" ){
		$fields = array();
		
		foreach( $this->fields as $field ){
			$fields[] = $field->install();
		}

		return implode($glue, $fields);
	}
	
	public function getPrepop(){
		return $this->prepop;
	}
	
	public function runPostInstall( \Snap\Adapter\Db $db ){
		foreach( $this->posts as $call ){
			$call( $db );
		}
	}

	protected function getOptions( $glue = ",\n" ){
		return parent::getOptions($glue);
	}
}
