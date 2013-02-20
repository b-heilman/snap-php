<?php

namespace Snap\Node\Page;

use Snap\Node;

class Basic extends Node\Core\Page 
	implements Node\Core\Styleable {
		
	protected function defaultTitle(){
		return 'Need a Title';
	}
	
	protected function getMeta(){
		return '<!-- No Meta Data -->';
	}
	
	public function getActions(){
		$res = parent::getActions();
 		$res[] = new \Snap\Lib\Linking\Resource\Local( '/jquery-ui.min.js' );
 		
 		return $res;
 	}
 	
 	public function getStyles(){
 		return array(
 			new \Snap\Lib\Linking\Resource\Local( '/reset.css' ),
 			new \Snap\Lib\Linking\Resource\Local( $this )
 		);
 	}
}