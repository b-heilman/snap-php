<?php

namespace Snap\Prototype\User\Node\Controller;

class LogoutForm extends \Snap\Node\Controller\Form {
	
	public function getOuputStream(){
		return 'user_logout'; // TODO : really?
	}
	
	protected function processInput( \Snap\Lib\Form\Result $formData ){
		\Snap\Prototype\User\Lib\Current::logout();
	
		return true;
	}
}