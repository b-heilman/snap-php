<?php

class test_form extends form_node 
	implements styleable_local_node {
	
	public function getLocalCSS(){
		return 'forms/test.css';
	}
	
	protected function processInput( form_data_result &$formData ){
		$this->debug( $formData->getErrors() );
		$this->debug( $formData->getValues() );
		
    	return null;
    }
}