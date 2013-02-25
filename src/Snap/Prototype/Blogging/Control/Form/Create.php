<?php

namespace Snap\Prototype\Blogging\Control\Form;

class Create extends \Snap\Prototype\Topic\Control\Form\Create {

	public function __construct( $type = null ){
		parent::__construct( $type );

		$this->setInputs(array(
				new \Snap\Lib\Form\Input\Formatted( 'content', '', function( $value ){
			return "<!-- translator : template -->\n".$value;
		})
		));
			
		$this->setValidations(array(
				new \Snap\Lib\Form\Validation\Generic('content', function( $value ){
			return $value == '';
		}, 'You need to submit some content')
		));
	}
}