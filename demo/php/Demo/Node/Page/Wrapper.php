<?php

namespace Demo\Node\Page;

class Wrapper extends \Snap\Node\Page\Basic {

	protected function defaultTitle(){
		return 'The example';
	}

	protected function getMeta(){
		return '';
	}
}