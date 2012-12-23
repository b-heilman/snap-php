<?php

namespace Demo\Node\Page;

class Mvc extends \Snap\Node\Page\Basic {

	protected function defaultTitle(){
		return 'The mvc page';
	}

	protected function getMeta(){
		return '';
	}
}