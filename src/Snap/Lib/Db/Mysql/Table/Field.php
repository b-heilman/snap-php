<?php

namespace Snap\Lib\Db\Mysql\Table;

class Field extends \Snap\Lib\Db\Table\Field {

	public function install(){
		$null = $this->null?'NULL':'NOT NULL';
		$options = $this->getOptions();

		return "{$this->name} {$this->type} $null $options";
	}

	public function alteration(){
		return '';
	}
	
	public function uninstall(){
		return '';
	}
}