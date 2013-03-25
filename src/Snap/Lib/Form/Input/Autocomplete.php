<?php

namespace Snap\Lib\Form\Input;

class Autocomplete extends Optionable 
	implements Composite {
	
	protected
		$text;
	
	public function __construct( $name, $value, $options, $multiple = false, $blockValue = null ){
		parent::__construct( $name, $value, $options, $multiple );
		
		$text = '';
		if ( !$multiple && $value !== $blockValue && isset($options[$value]) ){
			$text = $options[$value];
		}
		
		$this->text = new Basic( $name.'_text', $text );
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