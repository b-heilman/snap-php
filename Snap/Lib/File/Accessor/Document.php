<?php

namespace Snap\Lib\File\Accessor;

class Document extends Crawler {
	
	public function __construct( $path = null ){
		if ( !$path ){
			$path = $_GET['__file'];
		}
		
		$this->roots = static::$fileDocuments;
		
		parent::__construct( $path );
	}
	
	public function getLink( $root ){
		return $root.'__file='.urlencode( $this->path );
	}
}