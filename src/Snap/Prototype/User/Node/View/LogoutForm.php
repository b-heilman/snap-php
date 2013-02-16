<?php

namespace Snap\Prototype\User\Node\View;

class LogoutForm extends \Snap\Node\View\Form {
	
	protected function getTemplateVariables(){
		$args = parent::getTemplateVariables();
	
		$args['logoutText'] = 'Logout, '.$this->model->user->name();
	
		return $args;
	}
}