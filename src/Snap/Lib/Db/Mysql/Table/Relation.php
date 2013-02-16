<?php

namespace Snap\Lib\Db\Mysql\Table;

class Relation extends \Snap\Lib\Db\Table\Relation {

	protected function mysqlInstall(){
		$onUpdate = ($this->onUpdate != '')?'ON UPDATE '.$this->onUpdate:'';
		$onDelete = ($this->onDelete != '')?'ON DELETE '.$this->onDelete:'';

		return "FOREIGN KEY ({$this->tableField})
					REFERENCES {$this->foreignTable} ({$this->foreignField})
					$onUpdate $onDelete";
	}

	protected function mysqlUninstall(){
		return '';
	}
}