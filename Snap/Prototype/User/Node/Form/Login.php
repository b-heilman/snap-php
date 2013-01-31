<?php

namespace Snap\Prototype\User\Node\Form;

class Login extends \Snap\Node\Core\ProducerForm {
	
	public function getOuputStream(){
		return 'user_login';
	}
	
	protected function defaultValidator(){
		$validator = new \Snap\Lib\Form\Validator();
		
		$validator->setTest( '_login', new \Snap\Lib\Form\Test\Required('To log in, you need to suply a login') );
		$validator->setTest( '_passwrd', new \Snap\Lib\Form\Test\Required('To log in, you need to suply a password') );
		
		return $validator;
	}
	
	protected function processInput( \Snap\Lib\Form\Result &$formData ){
		$res = null;
		
		eval('$user = '.AUTH_CLASS.'::authenticate( $formData->getValue(\'_login\'), $formData->getValue(\'_passwrd\') );');
		
		if ( !$user )
			$formData->addError( $this->onLoginFailure() );
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