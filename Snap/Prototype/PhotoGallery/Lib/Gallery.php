<?php

namespace Snap\Prototype\PhotoGallery\Lib;

class Gallery {
	
	protected
		$accessor,
		$groups = array();
	
	public function __construct( \Snap\Lib\File\Accessor\Crawler $accessor ){
		$dirs = $accessor->scanDir();
		
		foreach( $dirs as $dir ) if ( $dir{0} != '.' ){
			$this->groups[] = new Group( $accessor->getChildAccessor($dir) );
		}
	}
	
	public function getGroups(){
		return $this->groups;
	}
}