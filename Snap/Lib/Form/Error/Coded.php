<?php

namespace Snap\Lib\Form\Error;

class Coded extends Simple {
	
	protected 
		$code,
		$namespace;
	
	public function __construct( $error, $code, $namespace ){
		parent::__construct( $error );
		
		$this->code = $code;
		$this->namespace = $namespace;
	}
	
	public function getCode(){
		return $this->code;
	}
	
	public function getNamespace(){
		return $this->namespace;
	}
}