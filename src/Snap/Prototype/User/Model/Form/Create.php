<?php

namespace Snap\Prototype\User\Model\Form;

class Create extends \Snap\Model\Form {
	
	public function __construct(){
		parent::__construct();
		
		$this->setInputs(array(
			new \Snap\Lib\Form\Input\Basic( 'name',      '' ),
			new \Snap\Lib\Form\Input\Basic( 'password1', '' ),
			new \Snap\Lib\Form\Input\Basic( 'password2', '' ),
		));
		
		$this->setValidations(array(
			new \Snap\Lib\Form\Validation\Paired( 'password1', 'password2', 'Passwords need to match' ),
			new \Snap\Lib\Form\Validation\Required( 'name', USER_LOGIN_LABEL.' needs to be filled in' )
		));
		
		if ( USER_LOGIN != USER_DISPLAY ){
			$this->setInputs(array(
				new \Snap\Lib\Form\Input\Basic( 'display', '' )
			));
			
			$this->setValidations(array(
					new \Snap\Lib\Form\Validation\Required( 'display', USER_DISPLAY_LABEL.' needs to be filled in' )
			));
		}
	}
}