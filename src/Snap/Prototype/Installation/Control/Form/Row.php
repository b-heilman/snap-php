<?php

namespace Snap\Prototype\Installation\Control\Form;

class Row extends \Snap\Control\Form {
	
	protected function processInput( \Snap\Lib\Form\Result $formData ){
		$changes = $formData->getChanges();
		
		if ( !empty($changes) ){
			$inputs = $formData->getInputs();
			$installs = array();
			$uninstalls = array();
			
			foreach( $changes as $field ){
				if ( $inputs[$field]->getValue() ){
					$installs[] = $field;
				}else{
					$uninstalls[] = $field;
				}
			}
			
			return new \Snap\Prototype\Installation\Lib\Management( $installs, $uninstalls, $this->model->prototype );
		}
	
		return null;
	}
}