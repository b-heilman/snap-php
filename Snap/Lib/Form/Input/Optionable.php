<?php

namespace Snap\Lib\Form\Input;

class Optionable extends \Snap\Lib\Form\Input {
	
	protected
		$options,
		$multiple;
	
	public function __construct( $name, $value, $options, $multiple = false ){
		$this->options = $options; // value => label
		$this->multiple = $multiple;
		
		if ( $multiple ){
			if ( !is_array($value) ){
				$value = array( $value );
			}
		}else{
			if ( is_array($value) ){
				$value = array_shift($value);
			}
		}
		
		parent::__construct( $name, $value );
	}
	
	public function getOptions(){
		return $this->options;
	}
	
	public function allowsMultiple(){
		return $this->multiple;
	}
	
	public function changeValue( $value ){
		if ( !$this->multiple && is_array($value) ){
			$this->currValue = array_shift( $value );
		}else{
			$this->currValue = $value;
		}
	}
	
	public function hasChanged(){
		return $this->currValue != $this->origValue; // so this way null can == ''
	}
}