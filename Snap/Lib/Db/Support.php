<?php

namespace Snap\Lib\Db;

abstract class Support {
	protected 
		$ops = array(),
		$mode = true;

	static public 
		$INSTALL_MODE = true,
		$UNINSTALL_MODE = false;
	
	abstract protected function mysqlInstall();
	abstract protected function mysqlUninstall();

	public function setMode( $mode  = false ){
		$this->mode = $mode;
	}

	protected function getOptions($glue = ' '){
		$options = array();

		foreach( $this->ops as $key => $val ){
			if ( is_numeric($key) ){
				$options[] = $val;
			}
		}

		return implode($glue, $options);
	}

	public function __toString(){
		if ( !isset($this->ops['designedFor']) )
			$this->ops['designedFor'] = 'mysql';

		switch( $this->ops['designedFor'] ){
			case 'mysql' :
			default :
				return ($this->mode)?$this->mysqlInstall():$this->mysqlUninstall();
		}
	}
}