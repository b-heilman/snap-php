<?php

namespace Snap\Lib\Db\Query\Select;

class Action implements \Snap\Lib\Db\Query\Where\Element{

	protected 
		$function, 
		$values;

	public function __construct( $function, $values ){
		$this->function = $function;
		$this->values = $values;
	}

	public function toString( \Snap\Adapter\Db $db ){
		return "{$this->function}( ".
			( is_array($this->values) ? implode(',',$this->values) : $this->values ).' )';
	}
}