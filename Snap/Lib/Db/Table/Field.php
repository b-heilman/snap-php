<?php

namespace Snap\Lib\Db\Table;

use \Snap\Lib\Db\Support;

abstract class Field extends Support{

	protected 
		$name,
		$type,
		$null = true,
		$ops = array();

	public function __construct($field, $type, $nullable = true, $options = array()){
		$this->name = $field;
		$this->type = $type;
		$this->null = $nullable;
		$this->ops = $options;
	}
}
