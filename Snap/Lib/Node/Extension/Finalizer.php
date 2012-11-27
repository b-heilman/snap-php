<?php

namespace Snap\Lib\Node\Extension;

use \Snap\Node\Snapable;

class Finalizer extends Base {

	protected function canHandle( Snapable $node ){
		return ( $node instanceof \Snap\Node\Finalizable );
	}
	
	protected function _run( Snapable $node ){
		$node->finalize();
	}
}