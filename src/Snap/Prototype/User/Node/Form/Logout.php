<?php

namespace Snap\Prototype\User\Node\Form;

class Logout extends \Snap\Node\Core\Form {
	
	protected function makeProcessContent(){
		$args = parent::makeProcessContent();
		error_log( 'making logout button' );
		$args['logoutText'] = 'Logout, '.$this->model->user->getDisplay();
	
		return $args;
	}
}