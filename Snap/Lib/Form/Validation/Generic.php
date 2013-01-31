<?php

namespace Snap\Lib\Form\Validation;

class Generic implements \Snap\Lib\Form\Validation {

	protected 
		$validation, 
		$errorMsg;
	
	public function __construct( $op, $errorMsg ){
		
		if ( is_string($op) ){
			$this->validation = function( $string ) use ( $op ) {
				return preg_match($op, $string);
			};
		}elseif( is_callable($op) ){
			$this->validation = $op;
		}else{
			throw new \Exception('a '.get_class($this).' needs either a regex string or function');
		}
		
		$this->errorMsg = $errorMsg;
	}
	
	public function isValid( $value ){
		$validation = $this->validation;
		error_log( print_r($validation, true) );
		return  $validation( $value );
	}
	
	public function getError(){
		return $this->errorMsg;
	}
}