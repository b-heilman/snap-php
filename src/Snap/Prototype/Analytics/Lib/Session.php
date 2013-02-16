<?php

namespace Snap\Prototype\Analytics\Lib;

use \Snap\Prototype\User\Lib\Current;

class Session {

	static protected 
		$snap_session,
		$id = null;
	
	protected static function init(){
		$snap_session = new \Snap\Lib\Core\Session('analytics');
		
		$id = $snap_session->getVar('id');
		if ( $id == null ){
			$id = self::createId();
			$snap_session->setVar('id', $id);
		}
		
		self::$id = $id;
	}
	
	protected static function createId(){
		$db = new \Snap\Adapter\Db\Mysql( ANALYTICS_DB );
			
		$user = Current::loggedIn()?
			Current::getUser()->id():null;
	
		$db->insert(ANALYTICS_TABLE, array(
			ANALYTICS_USER => $user,
			ANALYTICS_IP => $_SERVER['REMOTE_ADDR'],
			ANALYTICS_BROWSER => $_SERVER['HTTP_USER_AGENT'],
		));
		
		return $db->insertedID();
	}
	
	public static function getId(){
		self::init();
		
		return self::$id;
	}
}