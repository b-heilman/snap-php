<?php

namespace Snap\Lib\Form\Error;

class Validation implements \Snap\Lib\Form\Error {

	public 
		$test, 
		$node;
	
	public function __construct( \Snap\Lib\Form\Validation $test ){
		$this->test = $test;
	}
	
	public function getError(){
		return $this->test->getError();
	}
}