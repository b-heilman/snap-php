<?php

namespace Snap\Prototype\User\Control\Form;

use 
	\Snap\Adapter\Db\Mysql;

class Create extends \Snap\Control\Form {
	
	public function __construct( $settings = array() ){
		parent::__construct( $settings );
	
		if ( isset($settings['loginAfterCreation']) ){
			$this->loginAfterCreation( $settings['loginAfterCreation'] );
		}
	
		$this->admin = isset($settings['admin']) && $settings['admin'] ? 1 : 0;
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
	
		$user = new \Snap\Prototype\User\Model\Doctrine\User();
		
		$user->setLogin( $inputs['login']->getValue() );
		$user->setDisplay( $inputs['display']->getValue() );
		$user->setPassword( $inputs['password1']->getValue() );
		
		if ( $this->model->admin ) {
			$user->setAdmin( true );
		}
		
		try {
			$user->install();
			
			$formData->addNote( 'The Account Has Been Created' );
			 
			if ( $this->model->postLogin ){
				$formData->addNote( 'Logging In Automatically' );
				\Snap\Prototype\User\Lib\Current::login( $user );
			}
		}catch( \Exception $ex ){
			// TODO : this is hard coded for Mysql, need to change that
			if ( strpos(Mysql::lastError(), 'Duplicate entry') !== false ){
				if ( strpos(Mysql::lastError(), 'login') ){
					$formData->addFormError( 'That login exists already!' );
				}elseif ( strpos(Mysql::lastError(), 'display') ){
					$formData->addFormError( 'That display name exists already!' );
				}else{
					$formData->addFormError( 'That just will not work' );
					$formRes->addDebug( Mysql::lastError() );
				}
			}else{
				$formData->addFormError( 'Failure to create user' );
				$formRes->addDebug( Mysql::lastError() );
			}
		}
		 
		return $user->valid() ? $user : null;
	}
}