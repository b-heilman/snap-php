<?php

namespace Snap\Lib\Db\Query\Where;

class Action implements \Snap\Lib\Db\Query\Where\Element {

	protected 
		$variable, 
		$function, 
		$values;

	public function __construct( $variable, $function, $values ){
		$this->variable = $variable;
		$this->function = $function;
		$this->values = $values;
	}

	public function toString( \Snap\Adapter\Db $db ){
		return "{$this->variable} = {$this->function}( "
			.( is_array($this->values) ? implode(',',$this->values) : $values ).' )';
	}
}