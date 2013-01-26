<?php

namespace Snap\Lib\Db\Mysql\Table;

class Definition extends \Snap\Lib\Db\Table\Definition {
	
	protected function mysqlInstall(){
		if ( $this->engine == null ){
			$this->engine = 'MyISAM';
		}

		$fields  = $this->getFields();
		$options = $this->getOptions();

		if ( $options != '' ){
			$fields .= ",\n";
		}
		
		return "CREATE TABLE {$this->name}(
			$fields $options
		) ENGINE = {$this->engine};";
	}

	protected function mysqlUninstall(){
		return "DROP TABLE IF EXISTS `{$this->name}`;";
	}
}