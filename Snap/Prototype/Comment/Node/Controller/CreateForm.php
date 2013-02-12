<?php

namespace Snap\Prototype\Comment\Node\Controller;

class CreateForm extends \Snap\Node\Controller\Form {
	
	public function getOuputStream(){
		return 'new_comment'; // TODO : really?
	}
	
	protected function sanitizeComment( $comment ){
		return str_replace("\n", '<br>', htmlentities($comment) );
	}
	
	protected function processInput( \Snap\Lib\Form\Result $formData ){
		$res = null;
	
		if ( $formData->hasChanged('comment') && \Snap\Prototype\User\Lib\Current::loggedIn() ){
			$inputs = $formData->getInputs();
				
			$info = array(
					COMMENT_THREAD_ID => $inputs['thread']->getValue(),
					COMMENT_USER => \Snap\Prototype\User\Lib\Current::getUser()->id(),
					'content' => $this->sanitizeComment( $inputs['comment']->getValue() )
			);
				
			if ( isset($inputs['parent']) ){
				$info[COMMENT_PARENT] = $inputs['parent']->getValue();
			}
				
			if ( $id = \Snap\Prototype\Comment\Lib\Element::create($info) ){
				$res = new \Snap\Prototype\Comment\Lib\Element( $id );
	
				$formData->addNote( 'Comment Created' );
				$this->model->reset();
			}else{
				$formData->addError( 'Error creating comment' );
			}
		}else{
			$formData->addError( 'Your comment was blank' );
		}
	
		return $res;
	}
}