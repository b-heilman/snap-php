<?php

namespace Snap\Lib\Form;

class Validator {
	protected $tests = array();
	
	public function __construct( $tests = array() ){
		foreach( $tests as $field => $test ){
			if ( is_array($test) ){
				foreach( $test as $t ){
					$this->setTest( $field, $test );
				}
			}else{
				$this->setTest( $field, $test );
			}
		}
	}
	
	protected function setTest( $field, Validation $test ){
		if ( !isset($this->tests[$field]) ){
			$this->tests[$field] = array();
		}
		
		$this->tests[$field][] = $test;
	}
	
	public function validate( \Snap\Lib\Form\Result $res ){
		$inputs = $res->getInputs();
		
		foreach( $this->tests as $field => $tests ){
			if ( isset($inputs[$field]) ){
				/* @var $input \Snap\Node\Lib\Input */
				$input = $inputs[$field];
				$errored = false;
				
				foreach( $tests as $test ){
					if ( !$test->isValid($input->getValue()) ){
						$input->addError( new \Snap\Lib\Form\Error\Validation($test) );
						
						if ( !$errored ){
							$errored = true;
							$res->markInputError( $field );
						}
					}
				}
			}else{
				// TODO : need to do some sort of full validation...
			}
		}
	}
}