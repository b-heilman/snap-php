<?php

namespace Snap\Prototype\User\Control\Form;

use 
	\Snap\Adapter\Db\Mysql;

class Create extends \Snap\Control\Form {
	
	public function __construct( $settings = array() ){
		parent::__construct( $settings );
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'loginAfterCreation' => 'to log in as the user after creation'
		);
	}
	
	public function loginAfterCreation( $bool ){
		$this->login = $bool;
	}
	
	public function getOuputStream(){
		return 'user_added'; // TODO : really?
	}
	
	protected function processInput( \Snap\Lib\Form\Result $formData ){
		$inputs = $formData->getInputs();
		$model = $this->model;
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
						
			$formData->addDebug( $ex );
			
			// TODO : this is hard coded for Mysql, need to change that
			if ( strpos($error, 'Duplicate entry') !== false ){
				if ( strpos($error, "for key 'login'") ){
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
		 
		return $user->initialized() ? $user : null;
	}
}