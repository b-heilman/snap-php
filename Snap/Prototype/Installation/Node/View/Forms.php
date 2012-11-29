<?php

namespace Snap\Prototype\Installation\Node\View;

use \Snap\Node;

class Forms extends Node\View\Navigation 
	implements Node\Styleable {
	
	protected function baseClass(){
		return 'installation-forms';
	}
	
	public function getStyles(){
		return array(
			new \Snap\Lib\Linking\Resource\Local($this)
		);
	}
	
	protected function getTemplateVariables(){
		$var          = parent::getTemplateVariables();
		$prototype    = new \Snap\Prototype\Installation\Lib\Prototype( $this->getStreamData()->get(0) );
		
		if ( $prototype->forms ){
			$var['forms'] = array_keys( $prototype->forms );
		}
				
		return $var;
	}
}