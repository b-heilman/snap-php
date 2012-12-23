<?php

namespace Snap\Lib\Test;

class Generic implements \Snap\Lib\Form\Test {

	protected 
		$eval, 
		$errorMsg;
	
	public function __construct( $op, $errorMsg){
		if ( is_string($op) ){
			$this->eval = function( $string ) use ( $op ) {
				return preg_match($op, $string);
			};
		}elseif( is_callable($op) ){
			$this->eval = $op;
		}else{
			throw new \Exception('a '.get_class($this).' needs either a regex string or function');
		}
		
		$this->errorMsg = $errorMsg;
	}
	
	public function isValid( $value ){
		return $this->eval( $value );
	}
	
	public function getError(){
		return $this->errorMsg;
	}
}