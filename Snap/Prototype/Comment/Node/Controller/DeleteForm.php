<?php

namespace Snap\Prototype\Comment\Node\Controller;

class DeleteForm extends \Snap\Node\Controller\Form {

	public function getOuputStream(){
		return 'remove_comment';  // TODO : really?
	}

	protected function processInput( \Snap\Lib\Form\Result $formData ){
		$res = null;

		error_log( 'processing input' );
		if ( $formData->hasChanged('remove') ){
			$res = $this->model->comment;
			if ( !$res->delete() ){
				$res = null;
			}
		}

		return $res;
	}
}