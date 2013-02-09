<?php

namespace Snap\Lib\Form\Input;

class Stub extends Snap\Lib\Form\Input {
	
	public function hasChanged(){
		return true;
	}
	
	public function addError( \Snap\Lib\Form\Error $error ){
		// not allowed
	}
	
	public function hasError(){
		return false;
	}
}