<?php

namespace Snap\Prototype\Installation\Node\Controller;

class RowForm extends \Snap\Node\Controller\Form {
	
	protected function processInput( \Snap\Lib\Form\Result $formData ){
		$name = $this->model->prototype->name;
	
		if ( $formData->hasChanged($name) ){
			$inputs = $formData->getInputs();
			
			if ( $inputs[$name]->getValue() ){
				// install it
				return new \Snap\Prototype\Installation\Lib\Installer( $this->model->prototype );
			}else{
				// uninstall it
				return new \Snap\Prototype\Installation\Lib\Uninstaller( $this->model->prototype );
			}
		}
	
		return null;
	}
}