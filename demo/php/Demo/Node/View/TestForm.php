<?php

namespace Demo\Node\View;

class TestForm extends \Snap\Node\View\Form 
	implements \Snap\Node\Core\Styleable {
	
	public function getStyles(){
		return array(
			new \Snap\Lib\Linking\Resource\Local( $this->page, $this, 'Form/Testee.css' )
		);
	}
}