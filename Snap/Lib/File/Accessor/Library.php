<?php

namespace Snap\Lib\File\Accessor;

class Library extends Crawler {
	
	public function __construct( $path = null ){
		if ( !$path ){
			$path = $_GET['__file'];
		}
		
		$this->roots = static::$fileLibraries;
		
		parent::__construct( $path );
	}
	
	public function getLink( $root ){
		return $root.'__file='.urlencode( $this->path );
	}
}