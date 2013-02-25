<?php

namespace Snap\Prototype\Comment\Control\Form;

class Delete extends \Snap\Control\Form {

	public function getOuputStream(){
		return 'remove_comment';  // TODO : really?
	}

	protected function processInput( \Snap\Lib\Form\Result $formData ){
		if ( $formData->hasChanged('remove') ){
			$this->model->remove();
			$this->model->flush();
		}

		return $this->model;
	}
}