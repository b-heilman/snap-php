<?php

namespace Snap\Node\Form\Input;

class Autocomplete extends Select 
	implements \Snap\Node\Core\Styleable, \Snap\Node\Actionable\Inline, \Snap\Node\Core\Actionable {
	
	protected function parseSettings( $settings = array() ){
		if ( !($settings['input'] instanceof \Snap\Lib\Form\Input\Autocomplete) ){
			// TODO : this needs to be made into a generic pattern
			throw new \Exception( get_class($this)
				. ' needs model to be instance of type \Snap\Lib\Form\Input\Autocomplete, received '
				. get_class($settings['input']) 
			);
		}
		
		if ( !isset($settings['id']) ){
			$settings['id'] = $settings['input']->getName().'_auto';
		}
		
		parent::parseSettings( $settings );
	}
	
	public function getStyles(){
		return array( new \Snap\Lib\Linking\Resource\Local($this, 'jquery/jquery.selectAutocomplete.css') );
	}
	
	public function getActions(){
		return array( new \Snap\Lib\Linking\Resource\Local($this, 'jquery/jquery.selectAutocomplete.js') );
	}
	
	public function getInlineJavascript(){
		return "$( '#{$this->id}' ).selectAutocomplete({textName : '{$this->input->getText()->getName()}'});";
	}
}
