<?php

namespace Snap\Prototype\User\Node\Install;

class Row extends \Snap\Prototype\Installation\Node\Install\Row {
	
	protected 
		$username,
		$password;
	
	protected function processInput( \Snap\Lib\Form\Data\Result &$formData ){
		$rtn = parent::processInput( $formData );
		
		if ( $rtn instanceof \Snap\Prototype\Installation\Lib\Installer ){
			$rtn->addPostInstallHook( function( $db ) use ( $rtn, $formData ){
				$info = array(
	    			USER_ADMIN => 1
	    		);
		    		
	    		if ( USER_LOGIN != USER_DISPLAY ){
	    			$info[USER_DISPLAY] = $formData->getValue('install_user');
	    		}
	    		
	    		if ( $id = \Snap\Prototype\User\Lib\Element::create($formData->getValue('install_user'), $formData->getValue('install_pwd'), $info) ){
	    			\Snap\Prototype\User\Lib\Current::login( new \Snap\Prototype\User\Lib\Element($id) );
	    			
	    			return 'admin user installed';
	    		}else{
	    			return 'failed to install admin user';
	    		}
			});
		}
					
		return $rtn;
	}
}