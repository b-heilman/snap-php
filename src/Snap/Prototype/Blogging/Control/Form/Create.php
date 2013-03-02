<?php

namespace Snap\Prototype\Blogging\Control\Form;

class Create extends \Snap\Prototype\Topic\Control\Form\Create {

	protected function formatContent( $content){
		return "<!-- translator : template -->\n".$content;
	}
	
	protected function processInput( \Snap\Lib\Form\Result $formRes ){
		$inputs = $formRes->getInputs();
	
		$user = \Snap\Prototype\User\Lib\Current::getUser();
		$blog = new \Snap\Prototype\Blogging\Model\Doctrine\Blog();
		$blog->setContent( $this->formatContent($inputs['content']->getValue()) );
		$blog->setName( $this->formatTitle($inputs['name']->getValue()) );
		$blog->setType( $this->model->type
			? $this->model->type
			: \Snap\Prototype\Topic\Model\Doctrine\Type::find((int)$inputs['type']->getValue())
		);
	
		$thread = new \Snap\Prototype\Comment\Model\Doctrine\Thread();
		$thread->setUser( $user );
		$blog->setThread( $thread );
	
		$thread->persist();
		$blog->persist();
		$blog->flush();
	
		$formRes->addNote( 'Blog created' );
			
		$this->model->reset();
	
		return $blog;
	}
}