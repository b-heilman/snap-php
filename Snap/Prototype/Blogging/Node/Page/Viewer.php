<?php

namespace Snap\Prototype\Blogging\Node\Page;

class Viewer extends \Snap\Node\Page\Basic {
		
	protected 
		$blogType;
	
	protected function parseSettings( $settings = array() ){
		$this->blogType = isset($settings['blogType']) ? $settings['blogType'] : 'Blog';
		
		parent::parseSettings( $settings );
	}
	
	protected function defaultTitle(){
		return 'A Blog';
	}
	
	protected function getMeta(){
		return '';
	}
	
	protected function getTemplateVariables(){
		return array(
			'blogNavVar'  => 'topic',
			'blogContent' => 'topics_view'
		);
	}
}