<?php

namespace Demo\Node\Page;

class Index extends \Snap\Node\Page\Basic {

	protected function defaultTitle(){
		return 'The example';
	}

	protected function getMeta(){
		return '';
	}
}