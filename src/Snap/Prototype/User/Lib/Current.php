<?php

namespace Snap\Prototype\User\Lib;

use \Snap\Prototype\User\Model\Doctrine\User;

class Current extends \Snap\Lib\Core\StdObject {

	protected static
		$vars,
		$user = null,
		$logoutHooks = array();

	static public function init(){
		if ( is_null(static::$user) ){
			static::$vars = new \Snap\Lib\Core\Session('current_user_info');
			
			$id = static::$vars->getVar('id');
				
			if ( $id != null ){
				try{
					static::$user = User::find((int)$id);
				}catch( \Exception $ex ){
					error_log( 'system nuke' );
					// TODO : how can I tell if users is installed?
				}
			}
			
			if ( static::$user == null ){
				static::$user = new User();
			}
		}
	}

	static public function setVar( $var, $value ){
		static::init();
		
		static::$vars->setVar( $var, $value );
	}
	
	static public function getVar( $var ){
		static::init();
		
		return static::$vars->getVar( $var );
	}
	
	static public function isAdmin(){
		static::init();
		
		return ( static::$user->isAdmin() );
	}
	
	static public function login( User $user ){
		static::init();
			    	
		static::$user = $user;
		static::$vars->setVar( 'id', $user->getId() );
	}

	static public function addLogoutHook( $hook ){
		static::$logoutHooks[] = $hook;
	}

	static public function logout(){
		static::init();

		static::$vars->unsetVar( 'id' );
		static::$user = new User();

		foreach( static::$logoutHooks as $hook ){
			$hook();
		}
	}

	static public function loggedIn(){
		static::init();

		return !is_null( static::$vars->getVar('id') );
	}

	static public function secureCode(){
		static::init();

		return \Snap\Node\Core\Krypter::hashCode( self::$vars->getVar('id') );
	}

	/*****************
	 * @return users_element_proto
	 ***********/
	static public function getUser(){
		static::init();
		return static::$user;
	}
}