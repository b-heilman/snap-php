<?php

namespace Snap\Prototype\Installation\Node\Page;

use 
	\Snap\Node;

class Admin extends Node\Page\Basic
	implements Node\Core\Styleable {
	
	protected
		$login,
		$security;
	
	protected function getMeta(){
		return '';
	}
	
	protected function defaultTitle(){
		return 'Admin Console';
	}
	
	protected function makeTemplateContent(){
		$valid = true;
		try {
			$proto = new \Snap\Prototype\Installation\Lib\Prototype('\Snap\Prototype\User');
		}catch( \Exception $e ){
			$proto = null;
			$valid = false;
		}
		
		$login = new \Snap\Prototype\User\Model\Form\Login();
		$logout = new \Snap\Prototype\User\Model\Form\Logout();
		
		$this->login = new \Snap\Prototype\User\Node\Form\Login( array('model' => $login) );
		$this->security = function() use ( $proto ) {
			static $tableExists = null;
				
			if ( $proto && is_null($tableExists) ){
				$tableExists = !empty($proto->installs);
			}
				
			return \Snap\Prototype\User\Lib\Current::isAdmin() || !$tableExists;
		};
		
		return array(
			'logoutControl' => new \Snap\Prototype\User\Control\Form\Logout(array('model' => $logout)),
			'logoutView'    => new \Snap\Prototype\User\Node\Form\Logout(array('model' => $logout)),
			'loginControl'  => new \Snap\Prototype\User\Control\Form\Login(array('model' => $login)),
			'accessible' => $valid,
			'security'   => $this->security
		);
	}
	
	protected function _finalize(){
		$security = $this->security;
		
		if ( !$security() ){
			$this->clear();
	
			$this->append( $this->login );
		}
	
		parent::_finalize();
	}
}