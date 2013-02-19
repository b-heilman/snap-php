<?php

namespace Snap\Prototype\User\Control\Form;

class Login extends \Snap\Control\Form {

	public function getOuputStream(){
		return 'user_login'; // TODO : really?
	}

	protected function processInput( \Snap\Lib\Form\Result $formData ){
		$inputs = $formData->getInputs();
		
		$login = $inputs['login']->getValue();
		$pwd   = $inputs['password']->getValue();
		
		$user = \Snap\Prototype\User\Model\Doctrine\User::find( array('login' => $login) );
		
		if ( $user && $user->initialized() ){
			$auth = new \Snap\Prototype\User\Lib\Auth();
			
			if ( $auth->authenticate($user, $pwd) ){
				\Snap\Prototype\User\Lib\Current::login( $user );
				return $user;
			}else{
				$formData->addFormError( 'Login and password did not match' );
			}
		}else{
			$formData->addFormError( 'Login Failed' );
		}
		
		return null;
	}
}