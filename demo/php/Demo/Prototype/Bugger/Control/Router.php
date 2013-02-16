<?php

namespace Demo\Prototype\Bugger\Control;

class Router extends \Snap\Control\Router {
	
	public function __construct(){
		$this->setHome( function(){ return new \Demo\Prototype\Bugger\Node\Page\Test(); } );
		$this->addRoutes(array(
			// '/'     => something -> would be the same as the above line
			'admin' => function(){ return new \Snap\Prototype\Installation\Node\Page\Admin(); }
		));
	}
}