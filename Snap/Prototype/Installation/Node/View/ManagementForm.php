<?php

namespace Snap\Prototype\Installation\Node\View;

class ManagementForm extends \Snap\Node\View\Form 
	implements \Snap\Node\Core\Styleable {
	
	public function getStyles(){
		return array(
			new \Snap\Lib\Linking\Resource\Local( $this->page,$this)
		);
	}
}