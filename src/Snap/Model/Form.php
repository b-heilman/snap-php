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
		$series = array(),
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
	
	// TODO : This should be an add
	protected function setInputs( $inputs ){
		$this->addInputs( $inputs );
	}
	
	protected function addInputs( $inputs ){
		$submitted = $this->wasFormSubmitted();
		
		foreach( $inputs as $input ){
			$this->setInput( $input, $submitted, $this->uniqueness );
		}
	}
	
	protected function setSeries( $series, $inputs ){
		$submitted = $this->wasFormSubmitted();
		$s = array();
		
		if ( $this->uniqueness ){
			$sname = $series.$this->uniqueness;
		}else{
			$sname = $series;
		}
		
		$this->setInput( $control = new \Snap\Lib\Form\Input\Basic($sname,1), $submitted, null );
		
		foreach( $inputs as $input ){
			$s[] = $input->getName();
		}
		
		// doing this numerically, but trying to keep it so I can maybe make it a hash?
		for( $i = 0, $c = $control->getValue(); $i < $c; $i++ ){
			foreach( $inputs as $input ){
				$this->setInput( $input, $submitted, $sname.'_'.$i, $i );
			}
		}
		
		$this->series[ $series ] = $s;
	}
	
	public function getSeries( $series ){
		$inputs = $this->getResults()->getInputs();
		
		if ( $this->uniqueness ){
			$sname = $series.$this->uniqueness;
		}else{
			$sname = $series;
		}
		
		if ( isset($this->series[$series]) ){
			$t = array();
			$names = $this->series[$series];
			
			// doing this numerically, but trying to keep it so I can maybe make it a hash?
			for( $i = 0, $c = $inputs[$sname]->getValue(); $i < $c; $i++ ){
				$t2 = array();
				foreach( $names as $name ){
					$t2[$name] = $inputs[$name][$i];
				}
				$t[ $i ] = $t2;
			}
			
			return $t;
		}else return array();
	}
	
	private function setInput( \Snap\Lib\Form\Input $input, $submitted, $uniqueness, $series = null ){
		if ( $input instanceof \Snap\Lib\Form\Input\Composite ){
			$this->setInputs( $input->getSubcomponents() );
		}
			
		if ( $uniqueness ){
			$name = $input->getName();
				
			$input->addTag( $uniqueness );
				
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
			
		if ( $series ){
			if ( !isset($this->inputs[ $name ]) ){
				$this->inputs[ $name ] = array();
			}
			
			$this->inputs[ $name ][$series] = $input;
		}else{
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
	
	// TODO : this should be an add
	protected function setValidations( $validations ){
		if ( $this->validator ){
			$this->validator->add( $validations );
		}else{
			$this->validator = new \Snap\Lib\Form\Validator( $validations );
		}
	}
	
	protected function clearValidations(){
		$this->validator = null;
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