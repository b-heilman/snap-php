<?php

namespace Snap\Prototype\User\Node\View;

class LogoutForm extends \Snap\Node\Core\Form {
	
	protected function makeProcessContent(){
		$args = parent::makeProcessContent();
	
		$args['logoutText'] = 'Logout, '.$this->model->user->name();
	
		return $args;
	}
}