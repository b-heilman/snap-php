<?php

namespace Snap\Lib\Db\Table;

use \Snap\Lib\Db\Support;

abstract class Trigger extends Support{

	protected 
		$action,
		$when,
		$trigger,
		$table;

	public function __construct($table, $action, $when, $trigger, $options = array()){
		$this->table = $table;
		$this->action = $action;
		$this->when = $when;
		$this->trigger = $trigger;
		$this->ops = $options;
	}
}
