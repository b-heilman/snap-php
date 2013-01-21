<?php

namespace Snap\Lib\Mvc\Control;

// TODO : this is no longer needed
class NavigationFactory extends Factory {
	
	protected
		$url;
	
	public function __construct( \Snap\Node\Core\Producer $controller, \Snap\Lib\Navigation\Url $nav ){
		$this->url = $nav;
		
		parent::__construct( $controller );
	}
	
	public function getUrl(){
		return $this->url;
	}
	
	public function createLink( $value, $text = '' ){
		return $this->url->createLink( $value, $text, 'nav-control-link' );
	}
}