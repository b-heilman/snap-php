<?php

namespace Snap\Lib\Form\Error;

class Testable implements \Snap\Lib\Form\Error {

	public 
		$test, 
		$node;
	
	public function __construct( \Snap\Lib\Form\Test $test, \Snap\Node\Form\Input $node ){
		$this->test = $test;
		$this->node = ($node instanceof \Snap\Node\Form\WrappableInput) ? $node->getWrapper() : $node;
	}
	
	public function getError(){
		return $this->test->getError();
	}
}