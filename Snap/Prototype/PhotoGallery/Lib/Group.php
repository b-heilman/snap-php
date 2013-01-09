<?php

namespace Snap\Prototype\PhotoGallery\Lib;

class Group {
	
	protected
		$root,
		$icon = null,
		$title = null,
		$stats = array(),
		$images = array();
	
	public function __construct( $info ){
		if ( is_string($info ) ){
			$root = \Snap\Lib\Core\Bootstrap::getLibraryFile($info);
			$this->root = $info;
			// $info is a file path
			if ( file_exists($root.'/.info/titles') ){
				$stat = file($root.'/.info/titles');
				
				$this->icon = $info.'/.info/icon.jpg';
				$this->title = array_shift($stat);
				$this->stats = $stat;
			}
		}
	}
	
	public function getIcon(){
		return $this->icon;
	}
	
	public function getTitle(){
		return $this->title;
	}
	
	public function getImages(){
		if ( empty($this->images) ){
			$hashLookup = array();
			
			foreach( $this->stats as $line ){
				list( $file, $name ) = explode( ':', $line, 1 );
				$haskLookup[ $this->root.'/'.trim($file) ] = trim($name); 
			}
			
			$this->images = $hashLookup;
		}
		
		return $this->images;
	}
}