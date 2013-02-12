<?php

namespace Snap\Node\Page;

use Snap\Node;

class Basic extends Node\Core\Page 
	implements Node\Core\Actionable, Node\Core\Styleable {
		
	protected function defaultTitle(){
		return 'Need a Title';
	}
	
	protected function getMeta(){
		return '<!-- No Meta Data -->';
	}
	
	public function getActions(){
 		return array(
 			new \Snap\Lib\Linking\Resource\Local( $this,'/jquery.min.js'),
 			new \Snap\Lib\Linking\Resource\Local( $this,'/jquery-ui.min.js')
 		);
 	}
 	
 	public function getStyles(){
 		return array(
 			new \Snap\Lib\Linking\Resource\Local( $this,'/reset.css'),
 			new \Snap\Lib\Linking\Resource\Local( $this, $this )
 		);
 	}
}