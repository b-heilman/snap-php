<?php

namespace Snap\Lib\Linking\Resource;

class Remote {

	public
		$file;

	public function __construct( $file ){
		$this->file = $file;
	}
	
	public function getLink(){
		return $this->file;
	}
}