<?php

namespace Snap\Lib\Form\Input;

class Autocomplete extends Optionable 
	implements Composite {
	
	protected
		$text;
	
	public function __construct( $name, $value, $options, $multiple = false ){
		parent::__construct( $name, $value, $options, $multiple );
		
		$this->text = new Basic( $name.'_text', '' );
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