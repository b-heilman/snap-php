<?php

namespace Snap\Lib\File\Accessor;

class Web extends Crawler {

	public function __construct( $path = null ){
		$this->roots = array( static::$webRoot );
		
		parent::__construct( urldecode($path) );
	}

	// TODO : this isn't entirely right
	public function getLink( $root ){
		return $this->path ? '/'.$this->path : null;
	}
}