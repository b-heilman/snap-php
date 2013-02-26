<?php

namespace Snap\Prototype\Installation\Lib;

class Uninstaller {
	
	public
		$prototype,
		$tables;
	
	public function __construct( \Snap\Prototype\Installation\Lib\Prototype $prototype, array $tables ){
		$this->prototype = $prototype;
		$this->tables = $tables;
	}
	
	public function getTables(){
		return $this->tables;
	}
	
	public function getPrototype(){
		return $this->prototype;
	}
}