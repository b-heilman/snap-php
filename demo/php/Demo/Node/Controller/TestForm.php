<?php

namespace Demo\Node\Controller;

class TestForm extends \Snap\Node\Controller\Form {
	
	protected function processInput( \Snap\Lib\Form\Result &$formRes ){
		$data = array();
		foreach( $formRes->getInputs() as $input ){
			/* @var $input \Snap\Lib\Form\Input */
			$data[ $input->getName() ] = $input->getValue();
		}
		
		$changes = array();
		foreach( $formRes->getChanges() as $change ){
			/* @var $change \Snap\Lib\Form\Input */
			$changes[ $change->getName() ] = $change->getValue();
		}
		
		return array(
			'inputs'  => $data,
			'changes' => $changes,
			'errors'  => $formRes->getErrors()
		);
	}
	
}