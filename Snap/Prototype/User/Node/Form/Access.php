<?php

namespace Snap\Prototype\User\Node\Form;

class Access extends \Snap\Node\ProducerForm {
	
	protected function _finalize(){
		parent::_finalize();
		if( \Snap\Prototype\User\Lib\Current::loggedIn() ){
			$this->getElementByReference('login')->removeFromParent();
			
			$user = \Snap\Prototype\User\Lib\Current::getUser();
			$this->getElementByReference('logout')->getElementByReference('button')->changeText('Logout, '.$user->name());
			
			$this->addClass('login-active');
		}else{
			$this->getElementByReference('logout')->removeFromParent();
			
			$this->addClass('login-required');
		}
	}
}