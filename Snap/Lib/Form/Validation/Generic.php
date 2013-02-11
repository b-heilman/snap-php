<?php

namespace Snap\Lib\Form\Validation;

class Generic implements \Snap\Lib\Form\Validation {

	protected 
		$field,
		$validation, 
		$errorMsg;
	
	public function __construct( $field, $op, $errorMsg = null ){
		if ( $errorMsg == null ){
			$errorMsg = $op;
			$op = $field;
			$field = null;
		}
		
		if ( is_string($op) && $field ){
			$this->validation = function( $string ) use ( $op ) {
				return preg_match($op, $string);
			};
		}elseif( is_callable($op) ){
			$this->validation = $op;
		}else{
			throw new \Exception('a '.get_class($this).' needs either a regex string or function');
		}
		
		$this->field = $field;
		$this->errorMsg = $errorMsg;
	}
	
	public function checkForErrors( $inputs ){
		$validation = $this->validation;
		
		if ( $this->field ){
			if ( isset($inputs[$this->field]) && $validation($inputs[$this->field]->getValue()) ){
				return null;
			}else{
				return array( $this->field );
			}
		}else{
			return $validation( $inputs );
		}
	}
	
	public function getError(){
		return $this->errorMsg;
	}
}