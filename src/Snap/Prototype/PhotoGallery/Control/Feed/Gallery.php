<?php

namespace Snap\Prototype\PhotoGallery\Control\Feed;

class Gallery extends \Snap\Control\Feed {
	
	protected
		$gallery,
		$breakdown;
	
	protected function parseSettings( $settings = array() ){
		parent::parseSettings( $settings );
		
		if ( isset($settings['accessor']) ){
			if ( $settings['accessor'] instanceof \Snap\Lib\File\Accessor\Crawler ){
				$this->gallery = new \Snap\Prototype\PhotoGallery\Lib\Gallery( $settings['accessor'] );
			}elseif ( isset($settings['root']) ){
				$class = $settings['accessor'];
				$this->gallery = new \Snap\Prototype\PhotoGallery\Lib\Gallery( new $class($settings['root']) );
			}else{
				throw new \Exception('Gallery requires a accessor to work with');
			}
		}else{
			throw new \Exception('Gallery requires a accessor to work with');
		}
		
		$this->breakdown = isset($settings['breakdown']) ? $settings['breakdown'] : true;
	}
	
	protected function makeData(){
		return $this->breakdown 
			? new \Snap\Lib\Mvc\Data( $this->gallery->getGroups() )
			: new \Snap\Lib\Mvc\Data\Instance( $this->gallery );
	}
}