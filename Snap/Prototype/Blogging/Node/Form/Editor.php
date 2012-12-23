<?php

namespace Snap\Prototype\Blogging\Node\Form;

class Editor extends \Snap\Prototype\Topic\Node\Form\Create 
	implements \Snap\Node\Actionable {
		
	public function getActions(){
		return array(
			new \Snap\Lib\Linking\Resource\Local($this)
		);
	}
	
	protected function processInput( \Snap\Lib\Form\Data\Result &$formData ){
		$formData->alterValue('new_topic_content', 
			"<!-- translator : template -->\n".$formData->getValue('new_topic_content')
		);
		
		parent::processInput($formData);
	}
}