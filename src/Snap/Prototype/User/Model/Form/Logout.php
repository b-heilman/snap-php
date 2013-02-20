<?php

namespace Snap\Prototype\User\Model\Form;

class Logout extends \Snap\Model\Form {
	
	public
		$user;
	
	public function __construct(){
		$this->user = \Snap\Prototype\User\Lib\Current::getUser();
		
		parent::__construct();
		
		$this->setInputs(array(
			new \Snap\Lib\Form\Input\Checkbox( 'logout', $this->user ? $this->user->getId() : '' ) 
		));
	}
}
