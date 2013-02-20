<?php

namespace Snap\Prototype\User\Model\Form;

class Create extends \Snap\Model\Form {
	
	public 
		$admin,
		$postLogin;
	
	public function __construct( $admin = false, $login = false ){
		parent::__construct();
		
		$this->admin = $admin;
		$this->postLogin = $login;
		
		$this->setInputs(array(
			new \Snap\Lib\Form\Input\Basic( 'display', '' ),
			new \Snap\Lib\Form\Input\Basic( 'login', '' ),
			new \Snap\Lib\Form\Input\Basic( 'password1', '' ),
			new \Snap\Lib\Form\Input\Basic( 'password2', '' ),
		));
		
		$this->setValidations(array(
			new \Snap\Lib\Form\Validation\Paired( 'password1', 'password2', 'Passwords need to match' ),
			new \Snap\Lib\Form\Validation\Required( 'login', 'Login needs to be filled in' ),
			new \Snap\Lib\Form\Validation\Required( 'display', 'Display needs to be filled in' )
		));
	}
}