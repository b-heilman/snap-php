<?php

namespace Snap\Lib\Form\Data;

class Basic {
	
    protected 
    	$name, 
    	$default, 
    	$current;

    public function __construct($name, $value){
    	$this->name = $name;
		$this->default = $this->current = $value;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setValue( $value ){
		$this->current = $value;
	}
	
	public function setDefaultValue( $value ){
		$this->current = $this->default = $value;
	}
	
	public function getDefault(){
		return $this->default;
	}
	
	public function getValue(){
		return $this->current;
	}
	
	public function changeName( $name ){
		$this->name = $name;
	}
	
	public function hasChanged(){
		return $this->default != $this->current;
	}
	
	public function __toString(){
		return $this->getValue();
	}
}
