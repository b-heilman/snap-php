<?php

namespace Snap\Lib\Form\Error;

class Text extends \Snap\Lib\Form\Error {

	protected
		$error;
		
	public function __construct( $error ){
		$this->error = $error;
	}
	
	public function getError(){
		return $this->error;
	}
}