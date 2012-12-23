<?php

namespace Snap\Prototype\Blogging\Node\Page;

use 
	\Snap\Node;

class Editor extends Node\Page\Basic {
		
	protected 
		$login;
	
	public function getActions(){
		$actions = parent::getActions();
		$actions[] = new \Snap\Lib\Linking\Resource\Local( $this, '/tiny_mce/jquery.tinymce.js' );
		
		return $actions;
	}
	
	protected function defaultTitle(){
		return 'Build-A-Site';
	}
	
	protected function _finalize(){
		if ( !\Snap\Prototype\User\Lib\Current::isAdmin() ){
			$this->clear();
			
			$this->append( $this->login );
		}
		
		parent::_finalize();
	}
	
	protected function getMeta(){
		return '';
	}
}