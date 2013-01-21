<?php

namespace Snap\Lib\File\Accessor;

class Resource extends Crawler {

	public function __construct( $path = null ){
		$this->roots = static::$phpLibraries;
		
		parent::__construct( $path );
	}

	public function getLink( $root ){
		return $root . $this->path;
	}
}