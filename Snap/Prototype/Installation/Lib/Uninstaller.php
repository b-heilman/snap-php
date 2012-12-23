<?php

namespace Snap\Prototype\Installation\Lib;

class Uninstaller {
	
	public
		$prototype;
	
	public function __construct( \Snap\Prototype\Installation\Lib\Prototype $prototype ){
		$this->prototype = $prototype;
	}
	
	public function getPrototype(){
		return $this->prototype;
	}
}