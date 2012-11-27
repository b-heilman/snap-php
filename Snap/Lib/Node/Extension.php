<?php

namespace Snap\Lib\Node;

use \Snap\Node\Snapable;

interface Extension {
	static public function getInstance();
	
	public function addNode( Snapable $node );
	public function removeNode( Snapable $node );
	public function clear();
	
	public function run();
}