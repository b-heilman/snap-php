<?php

namespace Snap\Node\Form;

class Control extends \Snap\Node\Form\Row {

	protected function baseClass(){
		return 'form-control-row';
	}
	
	public function __construct( $settings = array() ){
		$name = isset($settings['name']) ? $settings['name'] : false;
		
		$buttons = isset($settings['buttons']) 
			? $settings['buttons'] : array( 'Submit' => 'submit', 'Reset' => 'reset');
			
		// $settings['alignment'] = count($buttons);
		
		parent::__construct($settings);
			
		$i = 0;
		foreach( $buttons as $val => $mode ){
			$this->append( new \Snap\Node\Form\Input\Button(array(
					'name'  => $name,
					'value' => $val,
					'mode'  => $mode,
			)) );
		}
	}
}