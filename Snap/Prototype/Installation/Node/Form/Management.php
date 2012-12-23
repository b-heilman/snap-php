<?php

namespace Snap\Prototype\Installation\Node\Form;

class Management extends \Snap\Node\Form 
	implements \Snap\Node\Styleable {
	
	public function getStyles(){
		return array(
			new \Snap\Lib\Linking\Resource\Local($this)
		);
	}
}