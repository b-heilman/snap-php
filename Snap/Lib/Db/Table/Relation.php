<?php

namespace Snap\Lib\Db\Table;

use \Snap\Lib\Db\Support;

abstract class Relation extends Support{

	protected 
		$tableField,
		$foreignField,
		$foreignTable,
		$onUpdate,
		$onDelete;

	public function __construct($tableField, $foreignTable, $foreignField,
								 $onUpdate = '', $onDelete = '', $options = array()){
		$this->tableField 	= $tableField;
		$this->foreignField = $foreignField;
		$this->foreignTable = $foreignTable;
		$this->onUpdate 	= $onUpdate;
		$this->onDelete 	= $onDelete;
		$this->ops			= $options;
	}

	public function table(){
		return $this->foreignTable;
	}
}
