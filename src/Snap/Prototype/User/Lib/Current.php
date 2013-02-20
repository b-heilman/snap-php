<?php

namespace Snap\Prototype\User\Lib;

use \Snap\Prototype\User\Model\Doctrine\User;

class Current {

	private static
		$vars,
		$user = null,
		$logoutHooks = array();

	static public function init(){
		if ( self::$user == null ){
			self::$user = new User();
			self::$vars = new \Snap\Lib\Core\Session('current_user_info');
			
			$id = self::$vars->getVar('id');
				
			if ( $id != null ){
				try{
					self::$user->duplicate( User::find((int)$id) );
				}catch( \Exception $ex ){
					// TODO : how can I tell if users is installed?
				}
			}
		}
	}

	static public function isAdmin(){
		self::init();
		
		if ( self::$user != null ){
			return ( self::$user->isAdmin() );
		}
		
		return false;
	}
	
	static public function login( User $user ){
		self::init();
			    	
		self::$user->duplicate( $user );
		self::$vars->setVar( 'id', $user->getId() );
	}

	static public function addLogoutHook( $hook ){
		self::$logoutHooks[] = $hook;
	}

	static public function logout(){
		self::init();

		self::$vars->unsetVar( 'id' );
		self::$user = null;

		foreach( self::$logoutHooks as $hook ){
			$hook();
		}
	}

	static public function loggedIn(){
		self::init();

		return !is_null( self::$vars->getVar('id') );
	}

	static public function secureCode(){
		self::init();

		return \Snap\Node\Core\Krypter::hashCode( self::$vars->getVar('id') );
	}

	/*****************
	 * @return users_element_proto
	 ***********/
	static public function getUser(){
		self::init();
		return self::$user;
	}
}