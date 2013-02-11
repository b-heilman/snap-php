<?php

namespace Snap\Lib\Form\Validation;

class Required implements \Snap\Lib\Form\Validation {

	protected 
		$field,
		$error;
	
	public function __construct( $field, $error = null ){
		$this->field = $field;
		$this->error = ( $error != null ) ? $error : 'required field';
	}
	
	/*
	 * Returns true if valid input, false otherwise
	 */
	public function checkForErrors( $inputs ){
		if ( $inputs[$this->field] ){
			$input = $inputs[$this->field];
			$value = $input->getValue();
			
			return is_null( $value ) || strlen(trim($value)) == 0 ? array($this->field) : null;
		}else{
			return null;
		}
	}
	
	public function getError(){
		return $this->error;
	}
}