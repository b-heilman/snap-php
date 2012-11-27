<?php

namespace Snap\Prototype\User\Lib;

class Element extends \Snap\Lib\Db\Element {

	static protected 
		$id_field = USER_ID,
		$name_field = USER_DISPLAY,
		$table = USER_TABLE,
		$db = null;

	public function __construct($data){
		if( is_string($data) ){
			$rtn = self::searchByLogin($data);
			if ( $rtn )
				$user = $rtn;
		}
		
		parent::__construct( $data );
	}
	
	public function updatePassword($password){
		return $this->update( array(USER_PASSWORD => users_auth_proto::encodePassword($password)) );
	}

	static protected function loadDB(){
		if ( self::$db == null ){
			self::$db = new \Snap\Adapter\Db\Mysql( USER_DB );
		}
	}
	
	public static function getId($search){
		$rtn = self::searchByLogin($search);
		
		if( $rtn ){
			return $rtn[USER_ID];
		}else{
			return false;
		}
	}
	
	public static function searchByLogin( $search ){
		static 
			$lastSearch = '', 
			$lastResult = '';
		
		if ( $lastSearch == $search ){
			return $lastResult;
		}else{
			self::callStatic('loadDB');
			$db = self::pullStatic('db');
	
			$res = $db->select(USER_TABLE, array(USER_LOGIN => $search));
	
			if( $res && $res->hasNext() ){
				return $lastResult = $res->next();
			}else{
				return false;
			}
		}
	}
	
	public static function create( $user, $password = null, $additional = array() ){
		if ( $password == null ){
			throw new \Exception( "Don't be an silly, you need a password" );
		}
		
		eval('$pwd = '.AUTH_CLASS.'::encodePassword($password);');

		return parent::create( array(USER_LOGIN => $user, USER_PASSWORD => $pwd) + $additional );
	}
}
