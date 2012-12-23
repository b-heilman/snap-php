<?php

namespace Snap\Lib\Db\Query\Select;

class Variable {

	protected 
		$value,
		$alias;

	public function __construct($value, $alias = null){
		if ( $value instanceof \Snap\Lib\Db\Query || $value instanceof \Snap\Lib\Db\Query\Select\Func ){
			if ( is_null($alias) ){
				throw new \Exception('Can not create db_query_select_variable with none string and not have alias');
			}else{
				$this->value = $value;
			}
		}elseif( !is_string($value) ){
			throw new \Exception('Data type not accepted');
		}else{
			$this->value = $value;
		}
			
		$this->alias = $alias;
	}

	public function getName(){
		return ( $this->alias == null ) ? $this->value : $this->alias;
	}

	public function toString( \Snap\Adapter\Db $db ){
		if ( $this->alias == null ){
			return "{$this->value}";
		}elseif ( is_string($this->value) ){
			return "{$this->value} AS {$this->alias}";
		}else{
			return '('.$this->value->toString($db).") AS {$this->alias}";
		}
	}
}