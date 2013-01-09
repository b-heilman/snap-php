<?php

namespace Snap\Prototype\PhotoGallery\Lib;

class Gallery {
	
	protected
		$groups = array();
	
	public function __construct( $info ){
		if ( is_string($info) ){
			// this is a directory that needs indexing
			$root = \Snap\Lib\Core\Bootstrap::getLibraryFile($info);
			
			$dirs = scandir( $root );
			foreach( $dirs as $dir ) if ( $dir{0} != '.' ){
				$this->groups[] = new Group( $info.'/'.$dir );
			}
		}
	}
	
	public function getGroups(){
		return $this->groups;
	}
}