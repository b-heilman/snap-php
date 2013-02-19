<?php

namespace Snap\Prototype\User\Model\Form;

class Row extends \Snap\Prototype\Installation\Model\Form\Row {
	
	public 
		$admin,
		$postLogin;
	
	public function __construct( $prototype = null ){
		parent::__construct( $prototype );
		
		// copying implementation of Create
		$this->admin = true;
		$this->postLogin = true;
		
		$this->setInputs(array(
			new \Snap\Lib\Form\Input\Basic( 'display', 'Admin' ),
			new \Snap\Lib\Form\Input\Basic( 'login', 'admin' ),
			new \Snap\Lib\Form\Input\Basic( 'password1', '' ),
			new \Snap\Lib\Form\Input\Basic( 'password2', '' )
		));
		
		$this->setValidations(array(
			new \Snap\Lib\Form\Validation\Paired( 'password1', 'password2', 'Passwords need to match' ),
			new \Snap\Lib\Form\Validation\Required( 'login', 'Login needs to be filled in' ),
			new \Snap\Lib\Form\Validation\Required( 'display', 'Display needs to be filled in' )
		));
	}
}