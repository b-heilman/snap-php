<?php

namespace Snap\Lib\File\Accessor;

class Resource extends Crawler {

	public function __construct( $path = null ){
		$this->roots = static::$phpLibraries;
		
		parent::__construct( $path );
	}

	public function getLink( $serviceRoot, $webRoot ){
		$server = $this->getConfig('//Server');
		
		if ( $server->resourceMode ){
			return $this->path ? $webRoot.'resources/'.$this->path : null;
		}else{
			return $this->path ? $serviceRoot.$this->path : null;
		}
	}
}