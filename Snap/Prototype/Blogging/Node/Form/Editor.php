<?php

namespace Snap\Prototype\Blogging\Node\Form;

class Editor extends \Snap\Prototype\Topic\Node\Form\Create 
	implements \Snap\Node\Core\Actionable {
		
	public function getActions(){
		return array(
			new \Snap\Lib\Linking\Resource\Local( $this->page,$this)
		);
	}
	
	protected function processInput( \Snap\Lib\Form\Result &$formData ){
		$formData->alterValue('new_topic_content', 
			"<!-- translator : template -->\n".$formData->getValue('new_topic_content')
		);
		
		parent::processInput($formData);
	}
	
	// If setting a type that doesn't exist, make it
	public function setType( $type ){
		parent::setType( $type );
		
		if (!$this->type) { // this is ok, 0 id is impossible new \Snap\Prototype\Topic\Lib\Type($type);
			$this->type = \Snap\Prototype\Topic\Lib\Type::create(array(
					TOPIC_TYPE_NAME => $type
			));
		}
	}
}