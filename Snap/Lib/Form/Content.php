<?php

namespace Snap\Lib\Form;

abstract class Content {
	
	protected static
		$adapter = null;
	
	protected
		$proc = null,
		$method = null,
		$inputs = array(),
		$values = array(),
		$formName = null,
		$validator = null,
		$controlInput;
	
	public function __construct(){
		// inputs are meant to be defined in the class
		
		if ( static::$adapter == null ){
			static::$adapter = new \Snap\Adapter\Form();
		}
		
		if ( $this->formName == null ){
			$this->setName( get_class($this) );
		}
		
		if ( $this->method == null ){
			$this->setMethod( 'POST' );
		}
		
		$this->controlInput = new \Snap\Lib\Form\Input\Basic( $this->formName, 1 );
	}
	
	protected function setValidations( $validations ){
		$this->validator = new Validator( $validations );
	}
	
	protected function setInputs( $inputs ){
		$submitted = $this->wasFormSubmitted();
		
		foreach( $inputs as $input ){
			/* @var $input \Snap\Lib\Form\Input */
			$name = $input->getName();
	
			if ( $submitted ){
				if ( $this->wasSubmitted($name) ){
					$input->changeValue( $this->getValue($name) );
				}else{
					$input->changeValue( null );
				}
			}
				
			$this->inputs[ $name ] = $input;
		}
	}
	
	protected function wasSubmitted( $name ){
		return $this->method ? static::$adapter->issetPost( $name ) : static::$adapter->issetGet( $name ) ;
	}
	
	protected function getValue( $name ){
		return $this->method ? static::$adapter->readPost( $name ) : static::$adapter->readGet( $name );
	}
	
	protected function setMethod( $method ){
		$this->method = ( strtoupper($method) == 'POST' );
	}
	
	protected function setName( $formName ){
		$this->formName = $formName;
	}
	
	protected function setValidator( \Snap\Lib\Form\Validator $validator ){
		$this->validator = $validator;
	}
	
	public function wasFormSubmitted(){
		return $this->wasSubmitted( $this->controlInput->getName() );
	}
	
	public function getMethod(){
		return ( $this->method ? 'POST' : 'GET' );
	}
	
	public function getInputs(){
		return $this->inputs;
	}
	
	public function getControlInput(){
		return $this->controlInput;
	}
	
	/**
	 * Return the results of the content being processed
	 * 
	 * @return \Snap\Lib\Form\Result
	 */
	public function getResults(){
		if ( $this->proc == null ) {
			if ( $this->validator && $this->wasFormSubmitted() ){
				$this->validator->validate( $this->inputs );
			}
			$this->proc = new \Snap\Lib\Form\Result( $this->inputs );
		}
		 
		return $this->proc;
	}
	
	// reset all of the current values to the original values
	public function reset(){
		foreach( $this->inputs as $input ){
			/* @var $input \Snap\Lib\Form\Input */
			$input->resetValue();
		}
	}
	
	
}