<?php

namespace Snap\Prototype\Installation\Node\Page;

use 
	\Snap\Node;

class Admin extends Node\Page\Basic
	implements Node\Core\Styleable {
	
	protected
		$login;
	
	protected function getMeta(){
		return '';
	}
	
	protected function defaultTitle(){
		return 'Admin Console';
	}
	
	protected function getTemplateVariables(){
		$valid = true;
		try {
			$proto = new \Snap\Prototype\Installation\Lib\Prototype('\Snap\Prototype\User');
		}catch( \Exception $e ){
			$proto = null;
			$valid = false;
		}
		
		$login = new \Snap\Prototype\User\Model\Form\Login();
		$logout = new \Snap\Prototype\User\Model\Form\Logout();
		
		$this->login = new \Snap\Prototype\User\Node\View\LoginForm( array('model' => $login) );
		
		return array(
			'logoutControl' => new \Snap\Prototype\User\Node\Controller\LogoutForm(array('model' => $logout)),
			'logoutView'    => new \Snap\Prototype\User\Node\View\LogoutForm(array('model' => $logout)),
			'loginControl'  => new \Snap\Prototype\User\Node\Controller\LoginForm(array('model' => $login)),
			'accessible' => $valid,
			'security'   => function() use ( $proto ) {
				static $tableExists = null;
				
				if ( $proto && is_null($tableExists) ){
					$tableExists = $proto->installed;
				}
				
				return \Snap\Prototype\User\Lib\Current::isAdmin() || !$tableExists;
			}
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