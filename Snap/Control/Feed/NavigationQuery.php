<?php

namespace Snap\Control\Feed;

abstract class NavigationQuery extends Query {
	
	protected 
		$url,
		$active;
	
	protected function parseSettings( $settings = array() ){
		if ( !isset($settings['navVar']) ){
			throw new \Exception( 'A navQuery requires a navVar' );
		}
		
		$navVar = $settings['navVar'];
		
		$this->url = new \Snap\Lib\Navigation\Url( $navVar );
		$this->active = isset($settings['active']) ? $settings['active'] : null;
		
		parent::parseSettings( $settings );
	}
	
	public function baseClass(){
		return get_called_class();
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'navVar' => 'The navigation variable'
		);
	}
	
	protected function defaultFactory(){
		return new \Snap\Lib\Mvc\Control\NavigationFactory( $this, $this->url );
	}
	
	protected function getUrlValue(){
		$var = $this->url->getValue();
		
		return $var == null ? $this->active : $var;
	}
}