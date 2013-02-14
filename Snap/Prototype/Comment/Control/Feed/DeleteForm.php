<?php

namespace Snap\Prototype\Comment\Control\Feed;

class DeleteForm extends \Snap\Control\Feed\Form {

	public function getOuputStream(){
		return 'remove_comment';  // TODO : really?
	}

	protected function processInput( \Snap\Lib\Form\Result $formData ){
		$res = null;

		if ( $formData->hasChanged('remove') ){
			$res = $this->model->comment;
			if ( !$res->delete() ){
				$res = null;
			}
		}

		return $res;
	}
}