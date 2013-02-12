<?php

namespace Snap\Prototype\User\Node\Controller;

class LoginForm extends \Snap\Node\Controller\Form {

	public function getOuputStream(){
		return 'user_login'; // TODO : really?
	}

	protected function processInput( \Snap\Lib\Form\Result $formData ){
		$res = null;
		
		$inputs = $formData->getInputs();
		
		$login = $inputs['name']->getValue();
		$pwd   = $inputs['password']->getValue();
		
		eval('$user = '.AUTH_CLASS.'::authenticate( $login, $pwd );');

		if ( !$user )
			$formData->addFormError( $this->onLoginFailure() );
		else{
			$res = new \Snap\Prototype\User\Lib\Element( $user );
			\Snap\Prototype\User\Lib\Current::login($res);
		}

		return $res;
	}

	protected function onLoginFailure(){
		eval('$msg = '.AUTH_CLASS.'::$failureReason;');

		return 'Login Failed: '.$msg;
	}
}