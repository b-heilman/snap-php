<?php

namespace Snap\Lib\Db\Mysql\Table;

class Field extends \Snap\Lib\Db\Table\Field {

	protected function mysqlInstall(){
		$null = $this->null?'NULL':'NOT NULL';
		$options = $this->getOptions();

		return "{$this->name} {$this->type} $null $options";
	}

	protected function mysqlUninstall(){
		return '';
	}
}