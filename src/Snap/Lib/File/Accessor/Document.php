<?php

namespace Snap\Lib\File\Accessor;

class Document extends Crawler {
	
	public function __construct( $path = null ){
		$this->roots = static::$fileDocuments;
		
		parent::__construct( $path );
	}
	
	public function getLink( $serviceRoot, $webRoot ){
		return $this->path ? $serviceRoot.$this->path : null;
	}
}