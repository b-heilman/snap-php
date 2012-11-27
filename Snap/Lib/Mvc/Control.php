<?php

namespace Snap\Lib\Mvc;

class Control extends \Snap\Lib\Mvc\Data {
	public function __construct( \Snap\Lib\Mvc\Control\Factory $factory = null, \Snap\Lib\Mvc\Data $data = null){
		parent::__construct();
		
		if ( $data != null ){
			$this->globals   = $data->globals;
			$this->stack     = $data->stack;
			$this->variables = $data->variables;
		}
		
		$this->setVar( 'factory', $factory );
	}
}