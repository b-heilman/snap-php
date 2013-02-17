<?php

namespace Snap\Prototype\User\Model\Form;

class Row extends \Snap\Prototype\Installation\Model\Form\Row {
	
	public function __construct( $prototype = null ){
		parent::__construct( $prototype );
	
		$this->setInputs(array(
			new \Snap\Lib\Form\Input\Basic( 'name', 'admin' ),
			new \Snap\Lib\Form\Input\Basic( 'password', '' )
		));
		
		$this->setValidations(array(
			new \Snap\Lib\Form\Validation\Required( 'name', 'Must set an admin' ),
			new \Snap\Lib\Form\Validation\Required( 'password', 'Must set a password' )
		));
	}
}