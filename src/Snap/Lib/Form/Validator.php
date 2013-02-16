<?php

namespace Snap\Lib\Form;

class Validator {
	protected $tests = array();
	
	public function __construct( $tests = array() ){
		$this->add( $tests );
	}
	
	public function add( $tests = array() ){
		if ( is_array($tests) ){
			$this->tests = array_merge($this->tests, $tests);
		}elseif( $tests instanceof Validation ) {
			$this->tests[] = $tests;
		}
	}
	
	public function validate( \Snap\Lib\Form\Result $res ){
		$inputs = $res->getInputs();
		
		foreach( $this->tests as $test ){
			/** @var $test \Snap\Lib\Form\Validation **/ 
			$errors = $test->checkForErrors( $inputs );
			
			if ( !is_null($errors) ){
				if ( empty($errors) ){
					$res->addFormError( new \Snap\Lib\Form\Error\Validation($test) );
				}else{
					for( $i = 0, $c = count($errors); $i < $c; $i++ ){
						$field = $errors[$i];
						
						if ( isset($inputs[$field]) ){
							$input = $inputs[$field];
							
							$input->addError( new \Snap\Lib\Form\Error\Validation($test) );
						
							$res->markInputError( $field );
						}
					}
				}
			}
		}
	}
}