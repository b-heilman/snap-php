<?php

namespace Snap\Prototype\PhotoGallery\Node\View;

class Group extends \Snap\Node\Core\View
	implements \Snap\Node\Accessor\Reflective {

	protected 
		$accessor = null;
	
	protected function parseSettings( $settings = array() ){
		if ( isset($settings['accessor']) ){
			if ( $settings['accessor'] instanceof \Snap\Lib\File\Accessor\Crawler ){
				$this->accessor = $settings['accessor'];
			}elseif ( isset($settings['root']) ){
				$class = $settings['accessor'];
				$this->accessor = new $class($settings['root']);
			}else{
				throw new \Exception('Group requires a accessor to work with');
			}
		}
		
		parent::parseSettings( $settings );
	}
	
	protected function baseClass(){
		return 'gallery-group';
	}

	protected function makeProcessContent(){
		if ( $this->accessor ){
			$group = new \Snap\Prototype\PhotoGallery\Lib\Group( $this->accessor );
		}else{
			$group = $this->getStreamData()->get(0);
		}
		
		$accessor = $group->getAccessor();
		$manager = $this->page->fileManager;
		$images = $group->getImages();
		$links = array();
		
		foreach ( $images as $link => $title ){
			$child = $accessor->getChildAccessor( $link );
			
			if ( $child ){
				$links[ $manager->makeLink($child) ] = $title;
			}
		}
		
		return array(
			'images'  => $links
		);
	}
}