<?php

namespace Snap\Prototype\Blogging\Node\View;

class Editor extends \Snap\Prototype\Topic\Node\Form\Create 
	implements \Snap\Node\Core\Actionable {
		
	public function getActions(){
		return array(
			new \Snap\Lib\Linking\Resource\Local($this->page,$this)
		);
	}
}