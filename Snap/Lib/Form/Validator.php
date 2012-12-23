<?php

namespace Snap\Lib\Form;

class Validator {
	protected $tests = array();
	
	public function __construct( $tests = array() ){
		foreach( $tests as $field => $test ){
			$this->setTest( $field, $test );
		}
	}
	
	public function setTest( $field, \Snap\Lib\Form\Test $test ){
		$this->tests[$field] = $test;
	}
	
	public function removeTest( $field ){
		unset( $this->tests[$field] );
	}
	
	public function validate( \Snap\Lib\Form\Data\Result $form_data ){
		$tests = $this->tests;
		
		foreach( $tests as $field => $test ){
			unset( $tests[$field] );
			$input = $form_data->getInput( $field );
		
			if ( !$test->isValid(is_null($input) ? null : $input->getValue()) ){
				$node = $form_data->getNode( $field );
				if ( $node != null ){
					$form_data->addError( new \Snap\Lib\Form\Error\Testable($test, $node) );
				}
			}
		}
	}
}