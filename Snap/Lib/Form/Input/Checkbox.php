<?php

namespace Snap\Lib\Form\Input;

class Checkbox extends \Snap\Lib\Form\Input {
	
	protected
		$selected;
	
	public function __construct( $name, $value, $selected = false ){
		parent::__construct( $name, $value );
		
		$this->selected = $this->currValue = $selected;
	}
	
	public function isSelected(){
		return $this->selected;
	}
	
	public function changeValue( $value ){
		if ( is_null($value) || $value != $this->origValue){
			$this->currValue = false;
		}else{
			$this->currValue = true;
		}
	}
	
	public function hasChanged(){
		return $this->selected !== $this->currValue;
	}
}