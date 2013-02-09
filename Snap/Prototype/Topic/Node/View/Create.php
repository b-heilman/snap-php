<?php

namespace Snap\Prototype\Topic\Node\View;

class Create extends \Snap\Node\Core\ProducerForm 
	implements \Snap\Node\Core\Styleable {
		
	public function getStyles(){
		return array(
			new \Snap\Lib\Linking\Resource\Local($this->page,$this)
		);
	}
}
