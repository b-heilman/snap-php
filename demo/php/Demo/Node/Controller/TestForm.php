<?php

namespace Demo\Node\Controller;

class TestForm extends \Snap\Node\Controller\Form {
	
	protected function processInput( \Snap\Lib\Form\Result $formRes ){
		$data = array();
		foreach( $formRes->getInputs() as $input ){
			/* @var $input \Snap\Lib\Form\Input */
			$data[ $input->getName() ] = $input->getValue();
		}
		
		error_log( 'TestForm' );
		$formRes->addFormError( 'Woot woot' );
		$formRes->addNote( '== note ==' );
		
		return array(
			'inputs'      => $data,
			'errors'      => $formRes->getFormErrors(),
			'changes'     => $formRes->getChanges(),
			'inputErrors' => $formRes->getInputErrors()
		);
	}
}