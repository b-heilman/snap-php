<?php

namespace Snap\Prototype\User\Node\View;

class LoginForm extends \Snap\Node\View\Form {
	
	public function getOuputStream(){
		return 'user_login';
	}
	
	protected function processInput( \Snap\Lib\Form\Result $formData ){
		$res = null;
		
		eval('$user = '.AUTH_CLASS.'::authenticate( $formData->getValue(\'_login\'), $formData->getValue(\'_passwrd\') );');
		
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