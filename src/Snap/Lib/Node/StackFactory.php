<?php

namespace Snap\Lib\Node;

// TODO : drop this like it is hot
class StackFactory {
	public function makeStack( \Snap\Node\Core\Snapable $node ){
		return new Stack( $node );
	}
}