<?php

namespace Snap\Lib\Db\Mysql\Table;

class Definition extends \Snap\Lib\Db\Table\Definition {
	
	public function install(){
		if ( $this->engine == null ){
			$this->engine = 'MyISAM';
		}

		$fields  = $this->getFields(",\n");
		$options = $this->getOptions(",\n");

		if ( $options != '' ){
			$fields .= ",\n";
		}
		
		return "CREATE TABLE `{$this->name}`($fields $options) ENGINE = {$this->engine};";
	}

	public function alteration(){
		$fields  = $this->getFields(",\nADD ");
		$options = $this->getOptions(",\nADD ");
		
		if ( $options != '' ){
			$fields .= ",\nADD ";
		}
		
		return "ALTER TABLE `{$this->name}` ADD $fields $options;";
	}
	
	public function uninstall(){
		return "DROP TABLE IF EXISTS `{$this->name}`;";
	}
}