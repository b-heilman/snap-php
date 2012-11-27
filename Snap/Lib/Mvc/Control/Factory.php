<?php

namespace Snap\Lib\Mvc\Control;

class Factory {

	protected 
		$controller = null;
	
	public function __construct( \Snap\Node\Producer $controller ){
		$this->master = $controller;
	}
	
	public function getController(){
		return $this->controller;
	}
}