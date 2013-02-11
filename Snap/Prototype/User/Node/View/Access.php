<?php

namespace Snap\Prototype\User\Node\View;

// TODO : this should become a virtual

class AccessForm extends \Snap\Node\View\Form {
	
	protected function _finalize(){
		parent::_finalize();
		
		if( \Snap\Prototype\User\Lib\Current::loggedIn() ){
			$this->getElementByReference('login')->removeFromParent();
			
			$this->addClass('login-active');
		}else{
			$this->getElementByReference('logout')->removeFromParent();
			
			$this->addClass('login-required');
		}
	}
	
	protected function getTemplateVariables(){
		$args = parent::getTemplateVariables();
	
		$args['loggedIn'] = function() {
			return \Snap\Prototype\User\Lib\Current::loggedIn();
		};
	
		return $args;
	}
}