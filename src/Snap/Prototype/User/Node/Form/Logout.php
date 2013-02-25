<?php

namespace Snap\Prototype\User\Node\Form;

class Logout extends \Snap\Node\Core\Form {
	
	protected function makeProcessContent(){
		$args = parent::makeProcessContent();
		$args['logoutText'] = 'Logout, '.\Snap\Prototype\User\Lib\Current::getUser()->getDisplay();
	
		return $args;
	}
}