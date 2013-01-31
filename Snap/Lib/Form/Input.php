<?php

namespace Snap\Lib\Form;

abstract class Input {
	
    protected 
    	$name,
    	$error, 
    	$currValue, 
    	$origValue;

    public function __construct( $name, $value ){
    	$this->name = $name;
    	$this->error = null;
		$this->currValue = $this->origValue = $value;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function changeValue( $value ){
		$this->currValue = $value;
	}
	
	public function getDefault(){
		return $this->origValue;
	}
	
	public function getValue(){
		return $this->currValue;
	}
	
	public function resetValue(){
		$this->currValue = $this->origValue;
	}
	
	public function hasChanged(){
		return $this->currValue != $this->origValue; // so this way null can == ''
	}
	
	public function setError( \Snap\Lib\Form\Error $error ){
		$this->error = $error;
	}
	
	public function hasError(){
		return $this->error != null;
	}
	
	/**
	 * 
	 * @return \Snap\Lib\Form\Error
	 */
	public function getError(){
		return $this->error;
	}
}
