<?php

namespace Snap\Prototype\User\Mode\Form;

class Logout extends \Snap\Model\Form {
	
	public
		$user;
	
	public function __construct(){
		$this->user = \Snap\Prototype\User\Lib\Current::getUser();
		
		parent::__construct();
		
		$this->addInputs(array(
			new \Snap\Lib\Form\Input\Checkbox( 'logout', $this->user->id() ) 
		));
	}
}
