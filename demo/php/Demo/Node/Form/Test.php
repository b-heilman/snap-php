<?php

namespace Demo\Node\Form;

class Test extends \Snap\Node\Core\Form 
	implements \Snap\Node\Core\Styleable {
	
	public function getStyles(){
		return array(
			new \Snap\Lib\Linking\Resource\Local( $this->page, $this, 'Form/Testee.css' )
		);
	}
	
	protected function processInput( \Snap\Lib\Form\Data\Result &$formData ){
		$this->debug( $formData->getErrors() );
		$this->debug( $formData->getValues() );
		
    	return null;
    }
}