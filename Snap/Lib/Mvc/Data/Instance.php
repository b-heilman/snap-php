<?php

namespace Snap\Lib\Mvc\Data;

class Instance extends \Snap\Lib\Mvc\Data {
	public function __construct( $data = null ){
		$this->init( is_null($data) ? null : array($data) );
	}
}
