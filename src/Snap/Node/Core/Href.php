<?php

namespace Snap\Node\Core;

class Href extends Block {
	
	protected
		$href = '',
		$title = '';
	
	protected function parseSettings( $settings = array() ){
		$settings['tag'] = 'a';
		
		parent::parseSettings( $settings );
		
		if ( isset($settings['text']) ){
			$this->write( $settings['text'] );
		}
		
		if ( isset($settings['href']) ){
			$this->href = $settings['href'];
		}
		
		if ( isset($settings['title']) ){
			$this->title = $settings['title'];
		}
	}
	
	protected function getAttributes(){
		// allow the link to be defined later for links, is someone really wants to do that
		if ( is_callable($this->href) ){
			$func = $this->href;
			$this->href = $func( $this->page );
		}
		
		return parent::getAttributes()." href=\"{$this->href}\" title=\"{$this->title}\"";
	}
}