<?php

namespace Snap\Prototype\User\Control\Form;

class Row extends \Snap\Prototype\Installation\Control\Form\Row {
	
	public function __construct( $settings = array() ){
		parent::__construct( $settings );
	}
	
	protected function processInput( \Snap\Lib\Form\Result $formData ){
		$rtn = parent::processInput( $formData );
		
		if ( $rtn instanceof \Snap\Prototype\Installation\Lib\Management && $rtn->hasInstaller() ){
			$model = $this->model;
			
			$rtn->getInstaller()->addPostInstallHook( function( $db ) use ( $rtn, $formData, $model ){
				// Code from Form/Create
				$inputs = $formData->getInputs();
				
				$user = new \Snap\Prototype\User\Model\Doctrine\User();
				
				$user->setLogin( $inputs['login']->getValue() );
				$user->setDisplay( $inputs['display']->getValue() );
				$user->setPassword( $inputs['password1']->getValue(), new \Snap\Prototype\User\Lib\Auth() );
				
				if ( $model->admin ) {
					$user->setAdmin( true );
				}
				
				try {
					$user->persist();
					$user->flush();
					
					$formData->addNote( 'The Account Has Been Created' );
				
					if ( $model->postLogin ){
						$formData->addNote( 'Logging In Automatically' );
						\Snap\Prototype\User\Lib\Current::login( $user );
					}
				}catch( \Exception $ex ){
					$error = $ex->getMessage();
					
					$formData->addDebug( $error.' : '.$ex->getFile().$ex->getLine() );
					
					// TODO : this is hard coded for Mysql, need to change that
					if ( strpos($error, 'Duplicate entry') !== false ){
						if ( strpos($error, 'login') ){
							$formData->addFormError( 'That login exists already!' );
						}elseif ( strpos($error, 'display') ){
							$formData->addFormError( 'That display name exists already!' );
						}else{
							$formData->addFormError( 'That just will not work' );
						}
					}else{
						$formData->addFormError( 'Failure to create user' );
					}
				}
			});
		}
			
		return $rtn;
	}
}