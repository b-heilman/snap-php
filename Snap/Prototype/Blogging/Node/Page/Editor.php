<?php

namespace Snap\Prototype\Blogging\Node\Page;

use 
	\Snap\Node;

class Editor extends Node\Page\Basic {
		
	protected 
		$login,
		$blogType;
	
	public function __construct( $settings = array() ){
		$this->login = new \Snap\Prototype\User\Node\Form\Access();
		
		parent::__construct( $settings );
	}
	
	protected function parseSettings( $settings = array() ){
		$this->blogType = isset($settings['blogType']) ? $settings['blogType'] : 'Blog';
		
		parent::parseSettings( $settings );
	}
	
	public function getActions(){
		$actions = parent::getActions();
		$actions[] = new \Snap\Lib\Linking\Resource\Local( $this, '/tiny_mce/jquery.tinymce.js' );
		
		return $actions;
	}
	
	protected function defaultTitle(){
		return 'Build-A-Site';
	}
	
	protected function getMeta(){
		return '';
	}
	
	protected function _finalize(){
		if ( !\Snap\Prototype\User\Lib\Current::isAdmin() ){
			$this->clear();
				
			$this->append( $this->login );
		}
	
		parent::_finalize();
	}
}