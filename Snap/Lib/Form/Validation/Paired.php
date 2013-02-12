<?php

namespace Snap\Lib\Form\Validation;

class Paired implements \Snap\Lib\Form\Validation {

	protected 
		$field1,
		$field2,
		$error;
	
	public function __construct( $field1, $field2, $error = null ){
		$this->field1 = $field1;
		$this->field2 = $field2;
		$this->error = ( $error != null ) ? $error : 'paired fields';
	}
	
	/*
	 * Returns true if valid input, false otherwise
	 */
	public function checkForErrors( $inputs ){
		if ( isset($inputs[$this->field1]) && isset($inputs[$this->field2]) 
			&& strcmp( $inputs[$this->field1]->getValue(), $inputs[$this->field2]->getValue() ) === 0 ){
			return null;
		}
		
		return array( $this->field1, $this->field2 );
	}
	
	public function getError(){
		return $this->error;
	}
}