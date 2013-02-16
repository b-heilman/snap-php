<?php

namespace Snap\Prototype\PhotoGallery\Lib;

class Group {
	
	protected
		$accessor,
		$icon = null,
		$title = null,
		$stats = array(),
		$images = array();
	
	public function __construct( \Snap\Lib\File\Accessor\Crawler $accessor ){
		$this->accessor = $accessor;
		$info = $accessor->getFullPath( '/.info/titles' );
			
		// $info is a file path
		if ( $info ){
			$stat = file( $info );
				
			$this->icon = $accessor->getChildAccessor( '/.info/icon.jpg' );
			$this->title = array_shift($stat);
			$this->stats = $stat;
		}
	}
	
	public function getAccessor(){
		return $this->accessor;
	}
	
	public function getIconAccessor(){
		return $this->icon;
	}
	
	public function getTitle(){
		return $this->title;
	}
	
	public function getImages(){
		if ( empty($this->images) ){
			$hashLookup = array();
			
			foreach( $this->stats as $line ){
				list( $file, $name ) = explode( ':', $line, 2 );
				$hashLookup[ trim($file) ] = trim($name); 
			}
			
			$this->images = $hashLookup;
		}
		
		return $this->images;
	}
}