<?php

namespace Demo\Model\Form;

class TestForm extends \Snap\Model\Form {
	public function __construct(){
		parent::__construct();
		
		$this->setInputs(array(
			new \Snap\Lib\Form\Input\Basic( 'text', 'text' ),  
			new \Snap\Lib\Form\Input\Basic( 'blankText', '' ),  
			new \Snap\Lib\Form\Input\Basic( 'password', 'password' ) ,  
			new \Snap\Lib\Form\Input\Basic( 'textarea', 'This is a text area or something like that' ),
			new \Snap\Lib\Form\Input\Checkbox( 'uncheckbox', 'uncheckbox', false ),
			new \Snap\Lib\Form\Input\Checkbox( 'checkbox', 'checkbox', true ),
			new \Snap\Lib\Form\Input\Optionable( 'select', 0, array(
				0  => 'Hello',
				1  => 'haha',
				2  => 'woot',
				'' => 'Pick One'
			) ),
			new \Snap\Lib\Form\Input\Optionable( 'multiSelect', '', array(
				0  => 'Null',
				1  => 'Eins',
				2  => 'Zwei',
				'' => 'Pick One Here'
			), true ),
			new \Snap\Lib\Form\Input\Optionable( 'pickable', 1, array(
					0  => 'Hello',
					1  => 'haha',
					2  => 'woot',
					'' => 'Pick One'
			) ),
			new \Snap\Lib\Form\Input\Optionable( 'multipickable', 2, array(
					0  => 'Null',
					1  => 'Eins',
					2  => 'Zwei',
					'' => 'Pick One Here'
			), true ),
			new \Snap\Lib\Form\Input\File( 'file' )
		));
		
		$this->setValidations(array(
			'text'     => new \Snap\Lib\Form\Validation\Required( 'Text needs to be entered' ),
			'pickable' => new \Snap\Lib\Form\Validation\Generic( function( $val ){ return $val === ''; }, 'Pickable is empty')
		));
	}
}