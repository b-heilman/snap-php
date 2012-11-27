<?php

namespace Snap\Lib\Db\Query\Where;

class Expression implements \Snap\Lib\Db\Query\Where\Element{

	protected 
		$variable, 
		$op, 
		$value;

	public function __construct( $variable, $op, $value ){
		$this->variable = $variable;
		$this->op = $op;
		$this->value = $value;
	}

	public function getVariable(){
		return $this->variable;
	}
	
	public function toString( \Snap\Adapter\Db $db ){
		return "{$this->variable} {$this->op} '{$db->escStr($this->value)}'";
	}
}