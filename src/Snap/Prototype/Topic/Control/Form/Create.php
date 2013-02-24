<?php

namespace Snap\Prototype\Topic\Control\Form;

class Create extends \Snap\Control\Form {
	
	protected function formatTitle( $title ){
		return $title;
	}
	
	protected function processInput( \Snap\Lib\Form\Result $formRes ){
		$res = null;
		
		$inputs = $formRes->getInputs();
		
		$topic = new \Snap\Prototype\Topic\Model\Doctrine\Topic();
		$topic->setName(  $this->formatTitle($inputs['title']->getValue()) );
		$topic->setType( $this->model->type ? $this->model->type : \Snap\Prototype\Topic\Model\Doctrine\Type::find((int)$inputs['type']->getValue()) );
		
		$topic->persist();
		$topic->flush();
		
		$formRes->addNote( 'Topic created' );
			
		$this->model->reset();
		
		return $topic;
	}
}