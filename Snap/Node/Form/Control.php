<?php

namespace Snap\Node\Form;

class Control extends \Snap\Node\Form\Row {
	static protected
		$instances = 0;
	
	protected function baseClass(){
		return 'form-control-row';
	}
	
	public function __construct( $settings = array() ){
		$name = isset($settings['name']) ? $settings['name'] : get_class($this).'_'.static::$instances++;
		
		$buttons = isset($settings['buttons']) 
			? $settings['buttons'] : array( 'Submit' => 'submit', 'Reset' => 'reset');
			
		// $settings['alignment'] = count($buttons);
		
		parent::__construct($settings);
			
		$i = 0;
		foreach( $buttons as $val => $type ){
			$this->append( new \Snap\Node\Form\Input\Button(array(
					'input' => new \Snap\Lib\Form\Input\Basic($name, $val),
					'text'  => $val,
					'type'  => $type,
			)) );
		}
	}
}