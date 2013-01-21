<?php

namespace Snap\Lib\File\Accessor;

class Document extends Crawler {
	
	public function __construct( $path = null ){
		$this->roots = static::$fileDocuments;
		
		parent::__construct( $path );
	}
	
	public function getLink( $root ){
		return $root . $this->path;
	}
}