<?php

namespace Snap\Prototype\Indexing\Control\Feed;

class Root extends \Snap\Control\Feed {
	protected function makeData(){
		return new \Snap\Lib\Mvc\Data(
			array_values( \Snap\Prototype\Indexing\Lib\Organizer::getLinks() )
		);
	}
}