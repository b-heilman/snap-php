<?php

namespace Snap\Prototype\Comment\Node\Controller;

class EditForm extends \Snap\Node\Controller\Form {
	
	protected function processInput( \Snap\Lib\Form\Result $formData  ){
		if ( $formData = parent::_process($data) ){
			if ( $formData->hasChanged('comment') ){
				$inputs = $formData->getInputs();
				
				if ( $this->model->comment->update(array(
					'content' => $inputs['comment']->getValue()
				)) ){
					$formData->addNote( 'Comment created' );
				}else{
					$formData->addFormError( 'Error creating comment' );
				}
			}
		}
	}
}
