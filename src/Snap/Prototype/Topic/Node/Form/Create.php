<?php

namespace Snap\Prototype\Topic\Node\Form;

class Create extends \Snap\Node\Core\Form 
	implements \Snap\Node\Core\Styleable {
		
	public function getStyles(){
		return array(
			new \Snap\Lib\Linking\Resource\Local($this)
		);
	}
}
