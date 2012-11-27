<?php

namespace Snap\Prototype\Indexing\Node\Controller;

class Root extends \Snap\Node\Controller {
	protected function makeData(){
		return new \Snap\Lib\Mvc\Data(
			array_values( \Snap\Prototype\Indexing\Lib\Organizer::getLinks() )
		);
	}
}