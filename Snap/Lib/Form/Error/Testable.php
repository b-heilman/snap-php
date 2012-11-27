<?php

namespace Snap\Lib\Error;

class Testable implements \Snap\Lib\Form\Error {

	public 
		$test, 
		$node;
	
	public function __construct( form_test $test, input_node $node ){
		$this->test = $test;
		$this->node = ($node instanceof wrappableInput_node) ? $node->getWrapper() : $node;
	}
	
	public function getError(){
		return $this->test->getError();
	}
}