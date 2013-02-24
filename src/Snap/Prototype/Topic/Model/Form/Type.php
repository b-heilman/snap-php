<?php

namespace Snap\Prototype\Topic\Model\Form;

class Type extends \Snap\Model\Form {
	
	public function __construct( $type = null ){
		parent::__construct();
	
		$this->setInputs(array(
			new \Snap\Lib\Form\Input\Basic( 'name', '' )
		));
		
		$this->setValidations(array(
			new \Snap\Lib\Form\Validation\Required( 'name', 'You need to name the type' )
		));
	}
}