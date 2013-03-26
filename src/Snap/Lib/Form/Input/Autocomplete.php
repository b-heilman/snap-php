<?php

namespace Snap\Lib\Form\Input;

class Autocomplete extends Optionable 
	implements Composite {
	
	protected
		$text,
		$ignoreValue;
	
	public function __construct( $name, $value, $options, $multiple = false, $ignoreValue = null ){
		parent::__construct( $name, $value, $options, $multiple );
		
		$this->ignoreValue = $ignoreValue;
		
		$text = '';
		if ( !$multiple && $value !== $ignoreValue && isset($options[$value]) ){
			$text = $options[$value];
		}
		
		$this->text = new Basic( $name.'_text', $text );
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