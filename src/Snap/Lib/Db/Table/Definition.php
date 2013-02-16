<?php

namespace Snap\Lib\Db\Table;

use \Snap\Lib\Db\Support;

abstract class Definition extends Support{

	protected 
		$fields    = array(),
		//$relations = array(), 
		//$triggers  = array(),
		$posts     = array(),
		$prepop    = array(),
		$name,
		$engine;

	public function __construct( $table, $options = array(), $engine = null){
		$this->name   = $table;
		$this->ops    = $options;
		$this->engine = $engine;
	}

	public function addField( Field $field ){
		$this->fields[] = $field;
	}
	/*
	public function addRelation ( Relation $con ){
		$this->relations[] = $con;
	}
	
	public function addTrigger ( Trigger $trigger ){
		$this->triggers[] = $trigger;
	}
	*/
	public function addPostInstall ( $func ){
		$this->posts[] = $func;
	}

	public function addPrepop ( $info ){
		$this->prepop = $info;
	}
	
	protected function getFields(){
		foreach( $this->fields as $field ){
			$field->setMode( $this->mode );
		}

		return implode(",\n", $this->fields);
	}
	/*
	protected function getRelations(){
		foreach( $this->relations as $relation ){
			$relation->setMode( $this->mode );
		}

		return implode(",\n", $this->relations);
	}
	
	public function getTriggersSQL(){
		foreach( $this->triggers as $trigger ){
			$trigger->setMode( $this->mode );
		}

		return implode("\n\n", $this->triggers);
	}
	*/
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
	/*
	public function getParentTables(){
		$res = array();

		foreach( $this->relations as $table ){
			$name = $table->table();
			if ( $name != $this->name ){
				$res[] = $name;
			}
		}

		return $res;
	}
	*/
}
