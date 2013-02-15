<?php

namespace Demo\Node\Page;

class Overlay extends \Snap\Node\Page\Basic {
	public function getActions(){
		$actions = parent::getActions();
		$actions[] = new \Snap\Lib\Linking\Resource\Local( $this );
	
		return $actions;
	}
}