<?php

namespace Snap\Prototype\User\Node\Controller;

use 
	\Snap\Adapter\Db\Mysql;

class CreateForm extends \Snap\Node\Controller\Form {
	
	protected
		$login = false,
		$admin = false;
	
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
		$res = null;
	
		$inputs = $formData->getInputs();
	
		$login = $inputs['name']->getValue();
		$password = $inputs['password1']->getValue();
		
		$data = array( 'admin' => $this->admin );
	
		if ( USER_LOGIN != USER_DISPLAY ){
			$data[USER_DISPLAY] = $inputs['display']->getValue();
		}
	
		if ( $id = \Snap\Prototype\User\Lib\Element::create($login, $password, $data) ){
			$res = new \Snap\Prototype\User\Lib\Element($id);
			 
			$formData->addNote( 'The Account Has Been Created' );
			 
			if ( $this->login ){
				$formData->addNote( 'Logging In Automatically' );
				\Snap\Prototype\User\Lib\Current::login( $res );
			}
		}else{
			// TODO : this is hard coded for Mysql, need to change that
			if ( strpos(Mysql::lastError(), 'Duplicate entry') !== false ){
				if ( strpos(Mysql::lastError(), USER_LOGIN) ){
					$formData->addFormError( 'That '.strtolower(USER_LOGIN_LABEL).' exists already!' );
				}elseif ( strpos(Mysql::lastError(), USER_DISPLAY) ){
					$formData->addFormError( 'That '.strtolower(USER_DISPLAY_LABEL).' exists already!' );
				}else{
					error_log( Mysql::lastError() );
					$formData->addFormError( 'That just will not work' );
				}
			}else{
				$formData->addFormError( 'Failure to create user' );
				error_log( Mysql::lastError() );
			}
		}
		 
		return $res;
	}
}