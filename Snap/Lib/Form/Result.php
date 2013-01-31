<?php

namespace Snap\Lib\Form;

use
	\Snap\Lib\Form\Error;

// Data type to be bassed back by a form_node
class Result {
	
	protected
		$data,
		$notes,
		$errors,
		$changes,
		$content;
	
	public function __construct( $inputs ){
		$this->data = array();
		$this->changes = array();
		$this->notes = array();
		
		$this->clearErrors(); // sets error = array()
		
		foreach( $inputs as $input ){
			/* @var $input \Snap\Lib\Form\Input */
    		$this->addInput( $input );
    	}	
	}

	protected function addInput( $in ){
		if ( $in instanceof Input ){
			$name = $in->getName();
	
			$this->insertion( $this->data, $name, $in );
	
			if ( $in->hasChanged() ){
				$this->insertion( $this->changes, $name, $in );
			}
	
			if ( $in->hasError() ){
				$this->addError( $in->getError() );
			}
		}
		/*
		if ( $in instanceof \Snap\Lib\Form\Input\Complex ) {
			$name = $in->getName();
			 
			$this->data[$name] = $in;
	
			if ( $in->hasChanged() ){
				$this->changes[$name] = $in;
			}
		}
		 */
	}
	
	protected function insertion( &$list, $name, Input $in ){
		// had this null protected, shouldn't really need it, so removed it
		if ( isset($list[$name]) ){
			if ( is_array($list[$name]) ){
				$list[$name][] = $in;
			}else{
				$list[$name] = array( $list[$name], $in );
			}
		}else{
			$list[$name] = $in;
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
		return $this->changes;
	}
	
	public function addError( $error ){
		if ( $error instanceof Error ){
			$this->errors[] = $error;
		}elseif( is_string($error )){
			$this->errors[] = new Error\Simple( $error );
		}else throw new \Exception( 'No idea how to handle error of type : '.get_class($error) );
	}
	
	public function clearErrors(){
		$this->errors = array();
	}
	
	public function hasErrors(){
		return !empty( $this->errors );
	}
	
	public function getErrors(){
		return $this->errors;
	}
	
	public function addNote( $note ){
		$notes[] = $note;
	}
	
	public function getNotes(){
		return $this->notes;
	}
}