<?php

namespace Snap\Prototype\Blogging\Node\View;

class EditorForm extends \Snap\Prototype\Topic\Node\View\CreateForm
	implements \Snap\Node\Core\Actionable {
		
	public function getActions(){
		return array(
			new \Snap\Lib\Linking\Resource\Local($this->page,$this)
		);
	}
}