<?php

namespace Snap\Control\Feed;

class Navigation extends \Snap\Control\Feed {
	
	protected 
		$url;
	
	protected function parseSettings( $settings = array() ){
		if ( !isset($settings['navVar']) ){
			throw new \Exception( 'A '.get_class($this).' requires a navVar' );
		}
		
		$navVar = $settings['navVar'];
		
		$this->url = new \Snap\Lib\Navigation\Url( $navVar );
		
		parent::parseSettings( $settings );
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'navVar' => 'The navigation variable'
		);
	}
	
	protected function makeData(){
		return new \Snap\Lib\Mvc\Data\Instance( $this->url->getValue() );
	}
	
	protected function defaultFactory(){
		return new \Snap\Lib\Mvc\Control\NavigationFactory( $this, $this->url );
	}
}