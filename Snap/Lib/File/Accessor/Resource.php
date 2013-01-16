<?php

namespace Snap\Lib\File\Accessor;

class Resource extends Crawler {

	public function __construct( $path = null ){
		if ( !$path ){
			$path = $_GET['__file'];
		}

		$this->roots = static::$phpLibraries;
		
		parent::__construct( $path );
	}

	public function getLink( $root ){
		return $root.'__file='.urlencode( $this->path );
	}
}