<?php

namespace Snap\Prototype\User\Lib;

class Current {

	private static
		$vars,
		$user = null,
		$logoutHooks = array();

	static public function init(){
		self::$user = new \Snap\Prototype\User\Lib\Element( null );
		self::$vars = new \Snap\Lib\Core\Session('current_user_info');
		
		if ( !self::$user->initialized() ){
			$id = self::$vars->getVar('id');
			
			if ( $id != null ){
				self::$user->duplicate( new \Snap\Prototype\User\Lib\Element($id) );
			}
		}
	}

	static public function isAdmin(){
		self::init();
		
		if ( self::$user != null ){
			return ( self::$user->info(USER_ADMIN) == 1 );
		}
		
		return false;
	}
	
	static public function login($user){
		self::init();
			    	
		self::$user->duplicate( $user = new \Snap\Prototype\User\Lib\Element($user) );
		
		self::$vars->setVar( 'id', $user->id() );
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