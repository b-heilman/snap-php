<?php

namespace Snap\Prototype\User\Node\Form;

use \Snap\Adapter\Db\Mysql;

class Create extends \Snap\Node\Core\ProducerForm {
	protected 
		$login = false,
		$admin = false;
	
	public function __construct( $settings = array() ){
		parent::__construct( $settings );
		
		if ( isset($settings['loginAfterCreation']) ){
			$this->loginAfterCreation( $settings['loginAfterCreation'] );
		}
		
		$this->admin = isset($settings['admin']) ? $settings['admin'] : false;
	}

	public function getOuputStream(){
		return 'user_added';
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'loginAfterCreation' => 'to log in as the user after creation'
		);
	}
	
	public function loginAfterCreation( $bool ){
		$this->login = $bool;
	}
	
	public function getInput(){
		$res = parent::getInput();
		
		if ( $this->admin ){
			$res->addInput( new \Snap\Lib\Form\Data\Basic('admin', 1) );
		}

		return $res;
	}
	
	protected function processInput( \Snap\Lib\Form\Data\Result &$formData ){
		$res = null;
		
		$login = $formData->getValue( USER_LOGIN );
        $password = $formData->getValue( USER_PASSWORD );

        $data = array();
        
        if ( USER_LOGIN != USER_DISPLAY ){
        	$data[USER_DISPLAY] = $formData->getValue( USER_DISPLAY );
        }
        
        if ( \Snap\Prototype\User\Lib\Element::create($login, $password, $data) ){
        	$res = new \Snap\Prototype\User\Lib\Element($id);
        	
        	$this->addNote('The Account Has Been Created');
        	
        	if ( $this->login ){
        		\Snap\Prototype\User\Lib\Current::login( new \Snap\Prototype\User\Lib\Element($id) );
        	}
    	}else{
			// TODO : this is hard coded for Mysql, need to change that
    		if ( strpos(Mysql::lastError(), 'Duplicate entry') !== false ){
    			if ( strpos(Mysql::lastError(), USER_LOGIN) ){
    				$formData->addError( 'That '.strtolower(USER_LOGIN_LABEL).' exists already!' );
    			}elseif ( strpos(Mysql::lastError(), USER_DISPLAY) ){
    				$formData->addError( 'That '.strtolower(USER_DISPLAY_LABEL).' exists already!' );
    			}else{
    				$formData->addError( Mysql::lastError() );
    				$formData->addError( 'That just will not work' );
    			}
    		}else{
    	    	$formData->addError( Mysql::lastError() );
    		}
    	}
	    
	    return $res;
	}
}