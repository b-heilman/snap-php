<?php

namespace Snap\Prototype\User\Node\Form;

class Logout extends \Snap\Node\ProducerForm {
	
	public function getOuputStream(){
		return 'user_logout';
	}
	
	protected function isInputReady( \Snap\Lib\Form\Data\Result $proc ){
		return true;
	}
	
	protected function processInput( \Snap\Lib\Form\Data\Result &$formData ){
		\Snap\Prototype\User\Lib\Current::logout();
		
		return true;
	}
}