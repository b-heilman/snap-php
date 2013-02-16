<?php

namespace Snap\Prototype\Installation\Node\View;

use \Snap\Node;

class Forms extends Node\View\Navigation 
	implements Node\Core\Styleable {
	
	protected function baseClass(){
		return 'installation-forms';
	}
	
	public function getStyles(){
		return array(
			new \Snap\Lib\Linking\Resource\Local( $this )
		);
	}
	
	protected function getTemplateVariables(){
		$info         = $this->getStreamData()->get(0);
		$var          = parent::getTemplateVariables();
		
		if ( $info ) {
			$prototype    = new \Snap\Prototype\Installation\Lib\Prototype( $info );
			
			if ( $prototype->forms ){
				$var['forms'] = array_keys( $prototype->forms );
			}
		}
		
		return $var;
	}
}