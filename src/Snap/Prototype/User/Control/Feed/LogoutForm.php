<?php

namespace Snap\Prototype\User\Control\Feed;

class LogoutForm extends \Snap\Control\Form {
	
	public function getOuputStream(){
		return 'user_logout'; // TODO : really?
	}
	
	protected function processInput( \Snap\Lib\Form\Result $formData ){
		\Snap\Prototype\User\Lib\Current::logout();
	
		return true;
	}
}