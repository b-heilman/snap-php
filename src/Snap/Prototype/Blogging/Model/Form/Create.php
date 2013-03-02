<?php

namespace Snap\Prototype\Blogging\Model\Form;

class Create extends \Snap\Prototype\Topic\Model\Form\Create {
	
	public function __construct( $type = null ){
		parent::__construct( $type );
		
		$this->setInputs(array(
			new \Snap\Lib\Form\Input\Basic( 'content', '' )
		));
			
		$this->setValidations(array(
			new \Snap\Lib\Form\Validation\Required( 'content', 'You need to submit some content' )
		));
	}
}