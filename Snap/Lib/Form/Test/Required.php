<?php

namespace Snap\Lib\Form\Test;

class Required implements \Snap\Lib\Form\Test {

	protected 
		$error;
	
	public function __construct( $error = null ){
		$this->error = ( $error != null ) ? $error : 'required field';
	}
	
	/*
	 * Returns true if valid input, false otherwise
	 */
	public function isValid( $value ){
		return is_null( $value ) ? false : (strlen(trim($value)) != 0 );
	}
	
	public function getError(){
		return $this->error;
	}
}