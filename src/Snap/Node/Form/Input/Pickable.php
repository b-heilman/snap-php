<?php

namespace Snap\Node\Form\Input;

class Pickable extends \Snap\Node\Form\Input\Basic {

	protected function parseSettings( $settings = array() ){
		$settings['tag'] = 'fieldset';
		$settings['type'] = 'junk';
		
		if ( !isset($settings['input']) || !($settings['input'] instanceof \Snap\Lib\Form\Input\Optionable) ){
			throw new \Exception( 'A '.get_class($this).' requires an instance of \Snap\Lib\Form\Input\Optionable,'
					.' instead recieved '.get_class($settings['input']) );
		}
		
		parent::parseSettings( $settings );
	}
	
	protected function baseClass(){
		return 'input-pickable';
	}
	
	protected function getAttributes(){
		// we need to step this back
    	return \Snap\Node\Core\Block::getAttributes();
    }
    
    // TODO : I want to make this loading in views, but for now...
    public function inner(){
    	$render = '';
    	$val = $this->input->getValue();
    	$name = $this->input->getName();
    	
    	if ( is_array($val) ){
    		$options = array();
    		foreach( $val as $v ){
    			$options[ $v ] = true;
    		}
    	}else{
    		$options = array( $val => true );
    	}
   
    	foreach( $this->input->getOptions() as $value => $label ){
    		$render .= ( $this->input->allowsMultiple() 
    			? $this->makeCheckbox( $name, $label, $value, isset($options[$value]) )
    			: $this->makeRadio( $name, $label, $value, isset($options[$value]) )
    		);
    	}
    		
    	$this->rendered = $render;
    
    	return parent::inner();
    }
    
    protected function makeCheckbox( $name, $label, $value, $checked ){
    	return "<label>$label<input type=\"checkbox\" value=\"".htmlentities($value)
    		.'" '.( $checked ? 'checked="checked"' : '' )." name=\"{$name}[]\" /></label>";
    }
    
    protected function makeRadio( $name, $label, $value, $checked ){
    	return "<label>$label<input type=\"radio\" value=\"".htmlentities($value)
    	.'" '.( $checked ? 'checked="checked"' : '' )." name=\"{$name}[]\" /></label>";
    }
}