<?php

namespace Snap\Lib\Node\Extension;

use \Snap\Node\Core\Snapable;

class Processor extends Base {
	
	protected function canHandle( Snapable $node ){
		return ( $node instanceof \Snap\Node\Core\Processable );
	}
	
	protected function _run( Snapable $node ){
		$node->process();
	}
}