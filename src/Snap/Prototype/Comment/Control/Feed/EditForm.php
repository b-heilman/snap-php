<?php

namespace Snap\Prototype\Comment\Control\Feed;

class EditForm extends \Snap\Control\Form {
	
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
