<?php

namespace Snap\Node;

class Content extends Template {
	
	protected
		$content;
	
	public function __construct( $settings = array() ){
		$settings['template'] = false;
		
		parent::__construct( $settings );
	}
	
	protected function parseSettings( $settings = array() ){
		$this->content = $settings['content'];
		
		parent::parseSettings();
	}
	
	protected function getContent(){
		return $this->content;
	}
}