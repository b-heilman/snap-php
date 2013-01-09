<?php

namespace Snap\Lib\Node\Extension;

use \Snap\Node\Snapable;

class Builder extends Base {

	protected function canHandle( Snapable $node ){
		return ( $node instanceof \Snap\Node\Block );
	}
	
	protected function _run( Snapable $node ){
		$node->build();
	}
}