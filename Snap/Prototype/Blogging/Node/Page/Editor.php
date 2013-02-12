<?php

namespace Snap\Prototype\Blogging\Node\Page;

use 
	\Snap\Node;

class Editor extends Node\Page\Basic {
		
	protected 
		$login,
		$blogType;
	
	protected function parseSettings( $settings = array() ){
		$this->blogType = isset($settings['blogType']) ? $settings['blogType'] : 'Blog';
		
		parent::parseSettings( $settings );
	}
	
	public function getActions(){
		$actions = parent::getActions();
		$actions[] = new \Snap\Lib\Linking\Resource\Local( $this, $this, '/tiny_mce/jquery.tinymce.js' );
		
		return $actions;
	}
	
	protected function defaultTitle(){
		return 'Build-A-Site';
	}
	
	protected function getMeta(){
		return '';
	}
	
	protected function getTemplateVariables(){
		$login = new \Snap\Prototype\User\Model\Form\Login();
		$logout = new \Snap\Prototype\User\Model\Form\Logout();
		$editor = new \Snap\Prototype\Topic\Model\Form\Create( $this->blogType );
		
		$this->login = new \Snap\Prototype\User\Node\View\LoginForm( array('model' => $login) );
		
		return array(
			'logoutControl' => new \Snap\Prototype\User\Node\Controller\LogoutForm(array('model' => $logout)),
			'logoutView'    => new \Snap\Prototype\User\Node\View\LogoutForm(array('model' => $logout)),
			'loginControl'  => new \Snap\Prototype\User\Node\Controller\LoginForm(array('model' => $login)),
			'editorView'    => new \Snap\Prototype\Blogging\Node\View\EditorForm(array('model' => $editor)),
			'editorControl' => new \Snap\Prototype\Topic\Node\Controller\CreateForm(array('model' => $editor))
		);
	}
	
	protected function _finalize(){
		if ( !\Snap\Prototype\User\Lib\Current::isAdmin() ){
			$this->clear();
				
			$this->append( $this->login );
		}
	
		parent::_finalize();
	}
}