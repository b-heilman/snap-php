<?php

namespace Demo\Node\View;

class CompositeForm extends \Snap\Node\Core\Form 
	implements \Snap\Node\Core\Styleable {
	
	public function getStyles(){
		return array(
			new \Snap\Lib\Linking\Resource\Local( $this, 'Form/Testee.css' )
		);
	}
}