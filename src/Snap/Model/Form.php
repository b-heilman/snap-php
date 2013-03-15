<?php

namespace Snap\Model;

// TODO : make this a singleton ?
abstract class Form {
	
	protected static
		$adapter = null;
	
	protected
		$proc = null,
		$method = null,
		$inputs = array(),
		$values = array(),
		$formName = null,
		$encoding = null,
		$validator = null,
		$uniqueness = null,
		$resultStream = null,
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
		
		if ( $this->uniqueness ){
			$this->controlInput = new \Snap\Lib\Form\Input\Basic( $this->formName.$this->uniqueness, 1 );
		}else{
			$this->controlInput = new \Snap\Lib\Form\Input\Basic( $this->formName, 1 );
		}
	}
	
	public function setResultStream( $stream ){
		$this->resultStream = $stream;
	}
	
	public function getResultStream(){
		return $this->resultStream;
	}
	
	protected function setUniqueTag( $tag ){
		$this->uniqueness = '_'.$tag;
		
		if ( $this->controlInput ){
			$this->controlInput->addTag( $this->uniqueness );
		}
	}
	
	protected function setInputs( $inputs ){
		$submitted = $this->wasFormSubmitted();
		
		foreach( $inputs as $input ){
			/* @var $input \Snap\Lib\Form\Input */
			
			if ( $input instanceof \Snap\Lib\Form\Input\Composite ){
				$this->setInputs( $input->getSubcomponents() );
			}
			
			if ( $this->uniqueness ){
				$name = $input->getName();
			
				$input->addTag( $this->uniqueness );
			
				$tagName = $input->getName();
			}else{
				$name = $tagName = $input->getName();
			}
			
			if ( $submitted ){
				if ( $this->wasSubmitted($tagName) ){
					$input->changeValue( $this->getValue($tagName) );
				}else{
					$input->changeValue( null );
				}
			}
				
			if ( $input instanceof \Snap\Lib\Form\Input\Encoded ){
				$this->encoding = $input->getEncoding();
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
	
	protected function setValidations( $validations ){
		if ( $this->validator ){
			$this->validator->add( $validations );
		}else{
			$this->validator = new \Snap\Lib\Form\Validator( $validations );
		}
	}
	
	public function getEncoding(){
		return $this->encoding;
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
			$this->proc = new \Snap\Lib\Form\Result( $this->inputs );
			
			if ( $this->validator && $this->wasFormSubmitted() ){
				$this->validator->validate( $this->proc );
			}
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