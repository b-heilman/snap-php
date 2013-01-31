<?php

namespace Snap\Node\Form\Input;

class Checkbox extends \Snap\Node\Form\Input\Basic {

	protected function parseSettings( $settings = array() ){
		if ( !isset($settings['input']) || !($settings['input'] instanceof \Snap\Lib\Form\Input\Checkbox) ){
			throw new \Exception( 'A '.get_class($this).' requires an instance of \Snap\Lib\Form\Input\Checkbox,'
				.' instead recieved '.get_class($settings['input']) );
		}
		$settings['type'] = 'checkbox';
		
		parent::parseSettings( $settings );
	}
	
	protected function getAttributes(){
    	return parent::getAttributes() . ( $this->input->isSelected() ? " checked=\"true\"" : '' );
    }
    
    protected function getInputValue(){
    	return htmlentities( $this->input->getDefault() );
    }
}