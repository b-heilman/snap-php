<?php

namespace Snap\Prototype\Installation\Node\Page;

use \Snap\Node;

class Admin extends Node\Page\Basic
	implements Node\Styleable {
	
	protected function getMeta(){
		return '';
	}
	
	protected function defaultTitle(){
		return 'Admin Console';
	}
}