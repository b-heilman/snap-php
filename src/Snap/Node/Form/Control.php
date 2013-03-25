<?php

namespace Snap\Node\Form;

class Control extends \Snap\Node\Form\Row {
	static protected
		$instances = 0;
	
	protected function baseClass(){
		return 'form-control-row';
	}
	
	public function __construct( $settings = array() ){
		if( !is_array($settings) ){
			$settings = array( 'input' => $settings );
		}
		
		parent::__construct( $settings );
	}
	
	protected function parseSettings( $settings = array() ){
		if ( isset($settings['input']) ){
			$input = $settings['input'];
			$name = $input->getName();
			
			foreach( $input->getOptions() as $value => $display ){
				$this->append( new \Snap\Node\Form\Input\Button(array(
					'input' => new \Snap\Lib\Form\Input\Basic($name, $value),
					'text'  => $display,
					'type'  => 'submit',
				)) );
			}
		}else{
			$name = 'formControl_'.static::$instances++;
			$this->append( new \Snap\Node\Form\Input\Button(array(
				'input' => new \Snap\Lib\Form\Input\Basic($name, 'submit'),
				'text'  => 'Submit',
				'type'  => 'submit',
			)) );
			$this->append( new \Snap\Node\Form\Input\Button(array(
				'input' => new \Snap\Lib\Form\Input\Basic($name, 'reset'),
				'text'  => 'Reset',
				'type'  => 'reset',
			)) );
		}
		
		parent::parseSettings($settings);
	}
}