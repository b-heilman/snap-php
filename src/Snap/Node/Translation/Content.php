<?php

namespace Snap\Node\Translation;

// TODO : I guess this goes here?
class Content extends \Snap\Node\Core\Template {
	
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
	
	protected function getTemplateHTML(){
		return $this->content;
	}
}