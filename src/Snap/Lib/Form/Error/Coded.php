<?php

namespace Snap\Lib\Form\Error;

class Coded extends Text {
	
	protected 
		$code,
		$namespace;
	
	public function __construct( $error, $code, \Snap\Lib\Form\Input $namespace ){
		parent::__construct( $error );
		
		$this->code = $code;
		$this->namespace = get_class( $namespace );
	}
	
	public function getCode(){
		return $this->code;
	}
	
	public function getNamespace(){
		return $this->namespace;
	}
}