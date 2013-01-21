<?php

namespace Demo\Node\View;

class Main extends \Snap\Node\Core\Template 
	implements \Snap\Node\Core\Styleable {
	
	public function getStyles(){
		return array(
			new \Snap\Lib\Linking\Resource\Local( $this->page,$this)
		);
	}
}