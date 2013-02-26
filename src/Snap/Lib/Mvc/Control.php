<?php

namespace Snap\Lib\Mvc;

class Control extends \Snap\Lib\Mvc\Data\Collection {
	public function __construct( \Snap\Lib\Mvc\Control\Factory $factory = null, \Snap\Lib\Mvc\Data $data = null){
		parent::__construct( $data );
		
		$this->setVar( 'factory', $factory );
	}
}