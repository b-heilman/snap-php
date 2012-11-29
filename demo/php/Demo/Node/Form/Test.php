<?php

namespace Demo\Node\Form;

class Test extends \Snap\Node\Form 
	implements \Snap\Node\Styleable {
	
	public function getStyles(){
		return array(
			new \Snap\Lib\Linking\Resource\Local( $this, 'Form/Testee.css' )
		);
	}
	
	protected function processInput( \Snap\Lib\Form\Data\Result &$formData ){
		$this->debug( $formData->getErrors() );
		$this->debug( $formData->getValues() );
		
    	return null;
    }
}