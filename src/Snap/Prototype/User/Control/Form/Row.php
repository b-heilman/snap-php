<?php

namespace Snap\Prototype\User\Control\Form;

class Row extends \Snap\Prototype\Installation\Control\Form\Row {
	
	public function __construct( $settings = array() ){
		parent::__construct( $settings );
	}
	
	protected function processInput( \Snap\Lib\Form\Result $formData ){
		$rtn = parent::processInput( $formData );
	
		if ( $rtn instanceof \Snap\Prototype\Installation\Lib\Installer ){
			$rtn->addPostInstallHook( function( $db ) use ( $rtn, $formData ){
				$inputs = $formData->getInputs();
				
				$info = array(
					USER_ADMIN => 1
				);
	
				if ( USER_LOGIN != USER_DISPLAY ){
					$info[USER_DISPLAY] = $inputs['name']->getValue();
				}
		   
				if ( $id = \Snap\Prototype\User\Lib\Element::create($inputs['name']->getValue(), $inputs['password']->getValue(), $info) ){
					\Snap\Prototype\User\Lib\Current::login( new \Snap\Prototype\User\Lib\Element($id) );
	
					$formData->addNote( 'Admin user installed' );
				}else{
					$formData->addNote( 'Failed to install admin user' );
				}
			});
		}
			
		return $rtn;
	}
}