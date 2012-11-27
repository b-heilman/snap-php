<?php

namespace Snap\Lib\Db\Query\Where;

class Nulled implements \Snap\Lib\Db\Query\Where\Element {
	protected $variable;

	public function __construct( $variable ){
		$this->variable = $variable;
	}

	public function toString( \Snap\Adapter\Db $db ){
		return "{$this->variable} IS NULL";
	}
}