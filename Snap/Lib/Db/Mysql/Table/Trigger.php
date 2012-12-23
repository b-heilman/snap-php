<?php

namespace Snap\Lib\Db\Mysql\Table;

class Trigger extends \Snap\Lib\Db\Table\Trigger {

	private function makeName(){
		return strtolower($this->when{0}.$this->action{0}.'_'.$this->table);
	}

	protected function mysqlInstall(){
		$name = $this->makeName();

		return <<<SQL
DROP TRIGGER IF EXISTS $name;
CREATE TRIGGER $name {$this->when} {$this->action} ON {$this->table}
FOR EACH ROW
BEGIN
	{$this->trigger}
END;
SQL;
	}

	protected function mysqlUninstall(){
		$name = $this->makeName();

		return "DROP TRIGGER IF EXISTS $name;";
	}
}