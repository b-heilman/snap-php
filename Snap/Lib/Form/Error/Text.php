<?php

namespace Snap\Lib\Form\Error;

class Text implements \Snap\Lib\Form\Error {

	protected
		$error;
		
	public function __construct( $error ){
		$this->error = $error;
	}
	
	public function getError(){
		return $this->error;
	}
}