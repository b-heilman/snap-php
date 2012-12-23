<?php

namespace Snap\Lib\Db\Query\Where;

class Join {
	static public 
		$AND = 0,
		$OR = 1;

	protected 
		$logic,
		$join;

	public function __construct( \Snap\Lib\Db\Query\Where\Element $logic, $join = 0 ){
		$this->logic = $logic;
		$this->join = $join;
	}

	public function toString( \Snap\Adapter\Db $db ){
		return ( ($this->join == \Snap\Lib\Db\Query\Where\Join::$AND)?' AND (':' OR (' ).
			$this->logic->toString( $db ).')';
	}
}