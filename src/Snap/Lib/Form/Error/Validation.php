<?php

namespace Snap\Lib\Form\Error;

class Validation extends \Snap\Lib\Form\Error {

	public 
		$test;
	
	public function __construct( \Snap\Lib\Form\Validation $test ){
		$this->test = $test;
	}
	
	public function getError(){
		return $this->test->getError();
	}
}