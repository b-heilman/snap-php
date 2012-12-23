<?php

namespace Snap\Lib\Mvc\Control;

class NavigationFactory extends Factory {
	
	protected
		$url;
	
	public function __construct( \Snap\Node\Producer $controller, \Snap\Lib\Navigation\Url $nav ){
		$this->url = $nav;
		
		parent::__construct( $controller );
	}
	
	public function getUrl(){
		return $this->url;
	}
	
	public function createLink( $value, $text = '' ){
		return $this->url->createLink($value, $text, 'nav-control-link');
	}
}