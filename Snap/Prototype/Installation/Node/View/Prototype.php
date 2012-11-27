<?php

namespace Snap\Prototype\Installation\Node\View;

use \Snap\Node;

class Prototype extends Node\View\Navigation 
	implements Node\Styleable {
	
	protected function setVariables(){
		$var = parent::setVariables();
		$var['prototype'] = $this->getStreamData()->get( 0 );
		
		return $var;
	}
	
	public function getStyles(){
		return array(
			new \Snap\Lib\Linking\Resource\Local($this)
		);
	}
}