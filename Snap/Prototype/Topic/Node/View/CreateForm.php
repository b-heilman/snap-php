<?php

namespace Snap\Prototype\Topic\Node\View;

class CreateForm extends \Snap\Node\View\Form 
	implements \Snap\Node\Core\Styleable {
		
	public function getStyles(){
		return array(
			new \Snap\Lib\Linking\Resource\Local($this->page,$this)
		);
	}
}
