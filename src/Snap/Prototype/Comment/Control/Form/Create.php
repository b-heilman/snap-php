<?php

namespace Snap\Prototype\Comment\Control\Form;

class Create extends \Snap\Control\Form {
	
	public function getOuputStream(){
		return 'new_comment'; // TODO : really?
	}
	
	protected function processInput( \Snap\Lib\Form\Result $formData ){
		$inputs = $formData->getInputs();
			
		$comment = new \Snap\Prototype\Comment\Model\Doctrine\Comment();
		$comment->setUser( \Snap\Prototype\User\Lib\Current::getUser() );
		$comment->setThread( $this->model->thread );
		$comment->setContent( $inputs['comment']->getValue() );

		if ( $this->model->parent ){
			$comment->setParent( $this->model->parent );
		}
		
		$comment->persist();
		$comment->flush();
		
		$formData->addNote( 'Comment Created' );
		$this->model->reset();
	
		return $comment;
	}
}