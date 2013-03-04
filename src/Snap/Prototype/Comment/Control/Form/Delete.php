<?php

namespace Snap\Prototype\Comment\Control\Form;

class Delete extends \Snap\Control\Form {

	public function getOuputStream(){
		return 'remove_comment';  // TODO : really?
	}

	protected function processInput( \Snap\Lib\Form\Result $formData ){
		if ( $formData->hasChanged('remove') ){
			$this->model->comment->remove();
			$this->model->comment->flush();
		}

		return $this->model->comment;
	}
}