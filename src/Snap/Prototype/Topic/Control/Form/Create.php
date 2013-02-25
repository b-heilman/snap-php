<?php

namespace Snap\Prototype\Topic\Control\Form;

class Create extends \Snap\Control\Form {
	
	protected function formatContent( $content){
		return $content;
	}
	
	protected function formatTitle( $title ){
		return $title;
	}
	
	protected function processInput( \Snap\Lib\Form\Result $formRes ){
		$inputs = $formRes->getInputs();
		
		$user = \Snap\Prototype\User\Lib\Current::getUser();
		$topic = new \Snap\Prototype\Topic\Model\Doctrine\Topic();
		$topic->setName( $this->formatTitle($inputs['name']->getValue()) );
		$topic->setType( $this->model->type 
			? $this->model->type 
			: \Snap\Prototype\Topic\Model\Doctrine\Type::find((int)$inputs['type']->getValue()) 
		);
		
		$thread = new \Snap\Prototype\Comment\Model\Doctrine\Thread();
		$thread->setUser( $user );
		$topic->setThread( $thread );
		
		$thread->persist();
		$topic->persist();
		$topic->flush();
		
		$formRes->addNote( 'Topic created' );
			
		$this->model->reset();
		
		return $topic;
	}
}