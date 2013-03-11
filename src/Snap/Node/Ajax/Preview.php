<?php

namespace Snap\Node\Ajax;

class Preview extends \Snap\Node\Core\Template 
	implements \Snap\Node\Core\Actionable {
	
	protected
		$linkList;
	
	protected function parseSettings( $settings = array() ){
		parent::parseSettings( $settings );
		
		$this->linkList = $settings['linkList'];
	}
	
	public function getActions(){
		return array( 
			new \Snap\Lib\Linking\Resource\Local($this, 'Ajax/Core.js'),
			new \Snap\Lib\Linking\Resource\Local($this, 'Ajax/Preview.js') 
		);
	}
}