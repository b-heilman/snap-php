<?php

namespace Snap\Prototype\Topic\Node\Form\Create;

class Type extends \Snap\Node\Core\Form{

	protected 
		$topics_new_form_proto = false;
	
	protected function processInput( \Snap\Lib\Form\Result &$formData ){
		if ( $formData->hasChanged('topics_type_new_name') ){
			$info = array(
				TOPIC_TYPE_NAME => $formData->getValue('topics_type_new_name'),
			);
			
			if ( \Snap\Prototype\Topic\Lib\Type::create($info) ){
				$this->prepend( new \Snap\Node\Core\Text('topics_element_proto type created') );
				$this->reset();
			}else{
				$this->prepend( new \Snap\Node\Core\Text(array(
					'text'  => 'Error creating topic type',
					'class' => 'error'
				)) );
			}
		}else{
			$this->prepend( new \Snap\Node\Core\Text(array(
				'text'  => 'You topic type was blank',
				'class' => 'error'
			)) );
		}
		
		return null;
	}
}