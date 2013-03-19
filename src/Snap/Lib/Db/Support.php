<?php

namespace Snap\Lib\Db;

abstract class Support {
	protected 
		$ops = array(),
		$mode = true;

	static public 
		$INSTALL_MODE = true,
		$UNINSTALL_MODE = false;
	
	abstract public function install();
	abstract public function uninstall();
	abstract public function alteration();
	
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
}