<?php

namespace Snap\Node\Form;

abstract class Input extends \Snap\Node\Core\Block {
	
	protected 
		$input;
	
	protected function parseSettings( $settings = array() ){
		$this->disabled = isset($settings['disabled']) ? $settings['disabled'] : false;
		$this->readonly = isset($settings['readonly']) ? $settings['readonly'] : false;
		
		if ( !isset($settings['input']) ){
			throw new Exception('A '.get_class($this).' needs an input');
		}
		$this->input = $settings['input'];
		/* @var $this->content \Snap\Lib\Form\Content */
		if ( !($this->input instanceof \Snap\Lib\Form\Input) ){
			throw new Exception("A form's content needs to be instance of \Snap\Lib\Form\Input");
		}
		
		parent::parseSettings( $settings );
	}
	
	protected function baseClass(){
		return 'form-input';
	}
	
	public static function getSettings(){
		parent::getSettings() + array(
			'input'    => 'the input to populate the element with \Snap\Lib\Form\Input',
			'disabled' => 'disable the input',
			'readonly' => 'make the input read only'
		);
	}
	
	protected function getAttributes() {
		return parent::getAttributes() . ( " name = \"{$this->getName()}\"" )
			.( $this->disabled ? ' disabled="true"' : '' )
    		.( $this->readonly ? ' readonly="true"' : '' );
	}
	
	protected function getName(){
		return $this->input->getName();
	}
}