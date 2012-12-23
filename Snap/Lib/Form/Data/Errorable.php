<?php

namespace Snap\Lib\Form\Data;

class Errorable extends Basic {
	
	protected
		$error;

	public function __construct( $name, $value ){
		$this->error = null;
		
		parent::__construct( $name, $value );
	}
	
	public function setError( \Snap\Lib\Form\Error $error ){
		$this->error = $error;
	}
	
	public function hasError(){
		return $this->error != null;
	}
	
	public function getError(){
		return $this->error;
	}
}