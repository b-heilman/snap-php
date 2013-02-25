<?php

namespace Snap\Prototype\Comment\Control\Form;

class Edit extends \Snap\Control\Form {
	
	protected function processInput( \Snap\Lib\Form\Result $formData  ){
		if ( $formData = parent::_process($data) ){
			if ( $formData->hasChanged('comment') ){
				$inputs = $formData->getInputs();
				
				$this->model->comment->setContent( $inputs['comment']->getValue() );
				$this->model->flush();
			}
		}
		
		return null;
	}
}
