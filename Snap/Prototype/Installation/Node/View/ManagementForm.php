<?php

namespace Snap\Prototype\Installation\Node\Form;

class ManagementForm extends \Snap\Node\Core\Form 
	implements \Snap\Node\Core\Styleable {
	
	public function getStyles(){
		return array(
			new \Snap\Lib\Linking\Resource\Local( $this->page,$this)
		);
	}
}