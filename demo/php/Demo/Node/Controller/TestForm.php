<?php

namespace Demo\Control\Feed;

class TestForm extends \Snap\Control\Feed\Form {
	
	protected function processInput( \Snap\Lib\Form\Result $formRes ){
		$data = array();
		foreach( $formRes->getInputs() as $name => $input ){
			/* @var $input \Snap\Lib\Form\Input */
			$data[ $name ] = $input->getValue();
		}
		
		//$formRes->addFormError( '== error ==' );
		$formRes->addNote( '== note ==' );
		
		return array(
			'inputs'      => $data,
			'errors'      => $formRes->getFormErrors(),
			'changes'     => $formRes->getChanges(),
			'inputErrors' => $formRes->getInputErrors()
		);
	}
}