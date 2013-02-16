<?php

namespace Snap\Lib\Mvc\Data;

class CompleteInstance extends Data {
	public function __construct( $data = null, $vars = null ){
		$this->init( (is_null($data) ? null : array($data)), $vars );
	}
}