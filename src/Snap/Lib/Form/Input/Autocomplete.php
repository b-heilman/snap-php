<?php

namespace Snap\Lib\Form\Input;

// TODO : a lot of this should go to the Node, can run off default value?
class Autocomplete extends Optionable 
	implements Composite {
	
	protected
		$text,
		$ignoreValue,
		$clearIgnore;
	
	public function __construct( $name, $value, $options, $multiple = false, $ignoreValue = null, $clearIgnore = false ){
		parent::__construct( $name, $value, $options, $multiple );
		
		$this->ignoreValue = $ignoreValue;
		$this->clearIgnore = $clearIgnore;
		
		$text = '';
		if ( !$multiple && ( $value !== $ignoreValue || $clearIgnore ) && isset($options[$value]) ){
			$text = $options[$value];
		}
		
		$this->text = new Basic( $name.'_text', $text );
	}
	
	public function clearIgnore(){
		return $this->clearIgnore;
	}
	
	public function getIgnoredValue(){
		return $this->ignoreValue;
	}
	
	public function textToOption( $value ){
		$this->options[ $value ] = $this->text->getValue();
		$this->changeValue( $value );
	}
	
	public function getSubcomponents(){
		return array( $this->text );
	}
	
	public function getText(){
		return $this->text;
	}
	
	public function hasChanged(){
		return parent::hasChanged() || $this->text->hasChanged();
	}
}