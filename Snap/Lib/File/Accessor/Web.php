<?php

namespace Snap\Lib\File\Accessor;

class Web extends Crawler {

	public function __construct( $path = null ){
		if ( !$path ){
			$path = $_GET['__file'];
		}

		$this->roots = array( static::$webRoot );
		
		parent::__construct( $path );
	}

	// TODO : this isn't entirely right
	public function getLink(){
		return '/'.$this->path;
	}
}