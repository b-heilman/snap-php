<?php

namespace Snap\Prototype\Topic\Control\Form;

class Type extends \Snap\Control\Form {
	
	protected function processInput( \Snap\Lib\Form\Result $formData ){
		$inputs = $formData->getInputs();
			
		$type = new \Snap\Prototype\Topic\Model\Doctrine\Type();
		$type->setName( $inputs['name']->getValue() );
		
		$type->persist();
		$type->flush();
			
		$formData->addNote( 'Added type '.$type->getName() );
		$this->model->reset();
		
		return $type;
	}
}