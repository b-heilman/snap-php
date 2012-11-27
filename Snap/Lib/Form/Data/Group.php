<?php

namespace Snap\Lib\Form\Data;

use 
	\Snap\Lib\Form\Error;

abstract class Group {
	
	protected 
		$form,
		$data, 
		$changes, 
		$errors;

    public function __construct( \Snap\Node\Form $form = null ){
    	$this->form = $form;
    	$this->data = array();
		$this->changes = array();
		
		$this->clearErrors();
	}
	
	public function addError( $error ){
		if ( $error instanceof Error ){
			$this->errors[] = $error;
		}elseif( is_string($error )){
			$this->errors[] = new Error\Simple($error);
		}else throw new \Exception( 'No idea how to handle error of type : '.get_class($error) );
	}
	
	protected function addInput( $in ){
		if ( $in instanceof Basic ){
			$name = $in->getName();
	    	
			$this->insertion( $this->data, $name, $in );
			
			if ( $in->hasChanged() ){
				$this->insertion( $this->changes, $name, $in );
			}
			
			if ( $in instanceof Errorable && $in->hasError() ){
				$this->addError( $in->getError() );
			}
		}
	}
	
	protected function insertion( &$list, $name, Basic $in ){
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
	
	public function getInputList(){
		return array_keys( $this->data );
	}
	
	public function getInputs(){
		return $this->data;
	}
	
	public function getValues(){
		return $this->pullValues( $this->data );
	}
	
	public function hasChanged(){
		return !empty( $this->changes );
	}
	
	public function getChanges(){
		return $this->changes;
	}
	
	public function getChangeList(){
		return array_keys( $this->changes );
	}
	
	public function getChangeValues(){
		return $this->pullValues( $this->changes );
	}
	
	protected function pullValues( $array ){
		$rtn = array();
		foreach( $array as $name => $input ){
			if ( $input instanceof Group ){
				$rtn[$name] = $input->getValues();
			}elseif ( is_array($input) ){
				$t = array();
				foreach( $input as $in ){
					$t[] = $in->getValue();
				}
				
				$rtn[$name] = $t;
			}else{
				$rtn[$name] = $input->getValue();
			}
		}
		
		return $rtn;
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
}