<?php

namespace Demo\Node\Controller;

class TestForm extends \Snap\Node\Controller\Form {
	
	protected function processInput( \Snap\Lib\Form\Result &$formRes ){
		$data = array();
		foreach( $formRes->getInputs() as $input ){
			/* @var $input \Snap\Lib\Form\Input */
			$data[ $input->getName() ] = $input->getValue();
		}
		
		return array(
			'inputs'      => $data,
			'errors'      => $formRes->getErrors(),
			'changes'     => $formRes->getChanges(),
			'inputErrors' => $formRes->getInputErrors()
		);
	}
}