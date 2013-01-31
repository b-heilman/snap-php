<?php

namespace Snap\Prototype\User\Node\Form;

class Logout extends \Snap\Node\Core\ProducerForm {
	
	public function getOuputStream(){
		return 'user_logout';
	}
	
	protected function isInputReady( \Snap\Lib\Form\Result $proc ){
		return true;
	}
	
	protected function processInput( \Snap\Lib\Form\Result &$formData ){
		\Snap\Prototype\User\Lib\Current::logout();
		
		return true;
	}
	
	protected function getTemplateVariables(){
		$args = parent::getTemplateVariables();
	
		$user = \Snap\Prototype\User\Lib\Current::getUser();
		$args['logoutText'] = 'Logout, '.$user->name();
	
		return $args;
	}
}