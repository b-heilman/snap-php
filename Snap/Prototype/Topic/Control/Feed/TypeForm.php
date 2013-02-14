<?php

namespace Snap\Prototype\Topic\Control\Feed;

class TypeForm extends \Snap\Control\Form {
	
	protected function processInput( \Snap\Lib\Form\Result $formData ){
		$res = null;
		
		if ( $formData->hasChanged('topics_type_new_name') ){
			$inputs = $formData->getInputs();
			
			$info = array(
				TOPIC_TYPE_NAME => $inputs['topics_type_new_name']->getValue(),
			);
			
			if ( $id = \Snap\Prototype\Topic\Lib\Type::create($info) ){
				$res = $id;
				$formData->addNote('Type created');
				$this->content->reset();
			}else{
				$formData->addError('Error creating topic type');
			}
		}else{
			$formData->addError('You topic type was blank');
		}
		
		return null;
	}
}