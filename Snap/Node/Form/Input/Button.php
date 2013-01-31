<?php

namespace Snap\Node\Form\Input;

class Button extends \Snap\Node\Form\Input\Basic {

	protected 
		$text;
	
	protected function parseSettings( $settings = array() ){
		// TODO : is this all the types?
		$settings['tag'] = 'button';
		$settings['type'] = ( isset($settings['type']) && $settings['type'] == 'reset' )
			? 'reset' : 'submit';
		
		parent::parseSettings($settings);
		
		$this->write( isset($settings['text']) ? $settings['text'] : $this->input->getDefault() );
	}
}