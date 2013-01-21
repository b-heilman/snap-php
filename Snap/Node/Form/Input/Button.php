<?php

namespace Snap\Node\Form\Input;

class Button extends \Snap\Node\Form\Input\Pickable {

	protected 
		$text;
	
	protected function parseSettings( $settings = array() ){
		// TODO : is this all the types?
		$settings['tag'] = 'button';
		$settings['type'] = ( isset($settings['type']) && $settings['type'] == 'reset' )
			? 'reset' : 'submit';
		
		parent::parseSettings($settings);
			
		$this->changeText( isset($settings['text']) ? $settings['text'] : $settings['value'] );
	}
	
	protected function baseClass(){
		return $this->type;
	}
	
	protected function getAttributes(){
    	return \Snap\Node\Form\Input\Basic::getAttributes();
    }
    
	public function changeText( $text ){
		$this->clear();
		
		if ( is_object($text) && $text instanceof \Snap\Node\Core\Snapable ){
			$this->append( $text );
		}else{
    		$this->write( $text );
		}
    }
}