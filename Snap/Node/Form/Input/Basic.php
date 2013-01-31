<?php

// was form_input_node
namespace Snap\Node\Form\Input;

class Basic extends \Snap\Node\Form\Input {
	
	protected 
		$type;
	
	protected function parseSettings( $settings = array() ){
		if ( !isset($settings['tag']) ){
			$settings['tag'] = 'input';
		}
		
		if ( isset($settings['type']) ){
			$this->type = strtolower( $settings['type'] );
		}else{
			throw new \Exception( get_class($this).' needs a type in settings: '.print_r($settings, true) );
		}
		
		parent::parseSettings($settings);
	}
	
	protected function baseClass(){
		return 'form-input-'.$this->getType();
	}
	
	public function getType(){
		return $this->type;
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'type' => 'the type of input'
		);
	}
	
	protected function getAttributes(){
		return parent::getAttributes() . " type=\"{$this->type}\" value=\"{$this->getInputValue()}\"";
	}
	
	protected function getInputValue(){
		return htmlentities( $this->input->getValue() );
	}
}