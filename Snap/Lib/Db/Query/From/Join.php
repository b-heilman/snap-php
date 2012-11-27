<?php

namespace Snap\Lib\Db\Query\From;

class Join {

	protected 
		$baseTable,
		$joinTable,
		$baseVariable,
		$joinVariable,
		$joinType;

	public function __construct( $baseTable, $baseVariable, $joinTable, $joinVariable, $joinType = 0 ){
		$this->baseTable = $baseTable;
		$this->joinTable = $joinTable;
		$this->baseVariable = $baseVariable;
		$this->joinVariable = $joinVariable;
		$this->joinType = $joinType;
	}

	public function toString( \Snap\Adapter\Db $db ){
		return ((From::$INNER_JOIN == $this->joinType)?' INNER JOIN ':' LEFT JOIN ').$this->joinTable
			." ON {$this->baseTable}.{$this->baseVariable} = {$this->joinTable}.{$this->joinVariable}";
	}
}
