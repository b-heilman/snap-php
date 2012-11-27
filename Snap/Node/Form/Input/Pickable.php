<?php

namespace Snap\Node\Form\Input;

abstract class Pickable extends \Snap\Node\Form\Input\Basic {

	protected 
		$checked,
		$trueValue;
	
	protected function parseSettings( $settings = array() ){
		$this->checked = isset($settings['checked']) ? $settings['checked'] : false;
		
		parent::parseSettings( $settings ); // we're gonna need to override a few things
	} 
	
	public static function getSettings(){
		return  parent::getSettings() + array(
			'checked' => 'is this input checked'
		);
	}
	
	public function setDefaultValue( $value ){
		$this->trueValue = $value;
		
		parent::setDefaultValue($value);
	}
	
	public function getName(){
    	return trim( $this->name, '[]' );
    }
    
	public function getInput( \Snap\Node\Form $form ){
		$name = $this->getName();
		
		if ( $form->wasFormSubmitted() ){
			if ( $form->wasSubmitted($name) ){
				$fv = $form->getValue( $name );
				
	    		if ( is_array($fv) ){
	    			$this->checked = ( array_search($this->trueValue, $fv) !== false );
	    		}else{
	    			$this->checked = ( $fv == $this->trueValue );
	    		}
	    	
	    		
			}else{
				$this->checked = false;
			}
		}
		
		$this->value->setValue( $this->getFunctionalValue() );
		
    	return $this->value;
    }
    
    protected function getFunctionalValue(){
    	return $this->checked ? $this->trueValue : (is_bool($this->trueValue)?!$this->trueValue:null);
    }
    
    protected function getInputValue(){
    	return htmlentities( $this->trueValue );
    }
    
    protected function getAttributes(){
    	return parent::getAttributes() . ( $this->checked ? " checked=\"true\"" : '' );
    }
}