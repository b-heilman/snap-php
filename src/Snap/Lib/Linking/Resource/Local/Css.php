<?php

namespace Snap\Lib\Linking\Resource\Local;

class Css extends \Snap\Lib\Linking\Resource\Local {
	public function __construct( $resource, $file = null ){
		parent::__construct( $resource, $file = null);
		
		$this->type = 'Css';
		$this->ext  = '.css';
	}
}