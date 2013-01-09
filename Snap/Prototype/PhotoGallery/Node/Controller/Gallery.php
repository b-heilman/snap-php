<?php

namespace Snap\Prototype\PhotoGallery\Node\Controller;

class Gallery extends \Snap\Node\Controller {
	
	protected
		$gallery,
		$breakdown;
	
	protected function parseSettings( $settings = array() ){
		parent::parseSettings( $settings );
		
		if ( isset($settings['gallery']) ){
			$this->gallery = new \Snap\Prototype\PhotoGallery\Lib\Gallery( $settings['gallery'] );
		}else{
			throw new \Exception('Gallery requires a gallery to work with');
		}
		
		$this->breakdown = isset($settings['breakdown']) ? $settings['breakdown'] : true;
	}
	
	protected function makeData(){
		return $this->breakdown 
			? new \Snap\Lib\Mvc\Data( $this->gallery->getGroups() )
			: new \Snap\Lib\Mvc\Data\Instance( $this->gallery );
	}
}