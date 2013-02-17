<?php

namespace Snap\Lib\Form\Input;

class Checkbox extends \Snap\Lib\Form\Input {
	
	protected
		$selectedInit;
	
	public function __construct( $name, $value, $selected = false ){
		parent::__construct( $name, $value );
		
		$this->selectedInit = $this->currValue = $selected;
	}
	
	public function isSelected(){
		return $this->currValue;
	}
	
	public function changeValue( $value ){
		if ( is_null($value) || $value != $this->origValue){
			$this->currValue = false;
		}else{
			$this->currValue = true;
		}
	}
	
	public function hasChanged(){
		return $this->selectedInit !== $this->currValue;
	}
}