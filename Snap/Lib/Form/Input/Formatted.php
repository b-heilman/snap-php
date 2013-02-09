<?php

namespace Snap\Lib\Form\Input;

class Formatted extends Snap\Lib\Form\Input {
	
	protected
		$formatting;
	
	public function __construct( $name, $value, $formatting ){
		parent::__construct( $name, $value );
		
		$this->formatting = $formatting;
	}
	
	public function changeValue( $value ){
		$func = $this->formatting;
		
		$this->currValue = $func( $value );
	}
}