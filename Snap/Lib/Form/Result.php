<?php

namespace Snap\Lib\Form;

use
	\Snap\Lib\Form\Error;

// Data type to be bassed back by a form_node
class Result {
	
	protected
		$data,
		$notes,
		$formErrors,
		$inputErrors,
		$changes,
		$content;
	
	public function __construct( $inputs ){
		$this->data = array();
		$this->notes = array();
		$this->changes = array();
		
		$this->clearErrors(); // sets error = array()
		
		foreach( $inputs as $name => $input ){
			/* @var $input \Snap\Lib\Form\Input */
    		$this->addInput( $name, $input );
    	}	
	}

	protected function addInput( $name, $in ){
		if ( $in instanceof Input ){
			$this->data[ $name ] = $in;
	
			if ( $in->hasChanged() ){
				$this->changes[ $name ] = true;
			}
			
			if ( $in->hasError() ){
				$this->markInputError( $name );
			}
		}
	}
	
	public function contains( $name ){
		return isset($this->data[$name]);
	}
	
	public function getInputs(){
		return $this->data;
	}
	
	public function hasChanged( $name = null ){
		if ( $name == null ){
			return !empty( $this->changes );
		}else{
			return isset( $this->changes[$name] );
		}
	}
	
	public function getChanges(){
		return array_keys( $this->changes );
	}
	
	public function markInputError( $name ){
		$this->inputErrors[ $name ] = true;
	}
	
	public function hasInputErrors(){
		return !empty( $this->inputErrors );
	}
	
	public function getInputErrors(){
		return array_keys( $this->inputErrors );
	}
	
	public function addFormError( $error ){
		if ( $error instanceof Error ){
			$this->formErrors[] = $error;
		}elseif( is_string($error )){
			$this->formErrors[] = new Error\Text( $error );
		}else throw new \Exception( 'No idea how to handle form error of type : '.get_class($error) );
	}
	
	public function hasFormErrors(){
		return !empty( $this->formErrors );
	}
	
	public function getFormErrors(){
		return $this->formErrors;
	}
	
	public function hasErrors(){
		return $this->hasInputErrors() || $this->hasFormErrors();
	}
	
	public function clearErrors(){
		$this->formErrors = array();
		$this->inputErrors = array();
	}
	
	public function addNote( $note ){
		$this->notes[] = $note;
	}
	
	public function getNotes(){
		return $this->notes;
	}
}