<?php

namespace Snap\Node\Form\Input;

class Button extends \Snap\Node\Form\Input\Basic {

	protected 
		$title;
	
	protected function parseSettings( $settings = array() ){
		// TODO : is this all the types?
		$settings['tag'] = 'button';
		$settings['type'] = ( isset($settings['type']) && $settings['type'] == 'reset' )
			? 'reset' : 'submit';
		
		parent::parseSettings($settings);
		
		$this->write( isset($settings['text']) ? $settings['text'] : 'Clicky' );
		$this->title = isset($settings['title']) ? $settings['title'] : null;
	}
	
	protected function getAttributes(){
		return parent::getAttributes() .( $this->title ? " title='{$this->title}'" : '' );
	}
	
	protected function getInputValue(){
		return htmlentities( $this->input->getDefault() );
	}
}