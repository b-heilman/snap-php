<?php

namespace Snap\Prototype\User\Model\Form;

class Login extends \Snap\Model\Form {
	
	public function __construct(){
		parent::__construct();
		
		$this->setInputs(array(
			new \Snap\Lib\Form\Input\Basic( 'login', '' ),
			new \Snap\Lib\Form\Input\Basic( 'password', '' )
		));
		
		$this->setValidations(array(
			new \Snap\Lib\Form\Validation\Required( 'login', 'needs to be entered' ),
			new \Snap\Lib\Form\Validation\Required( 'password', 'needs to be entered' )
		));
	}
}