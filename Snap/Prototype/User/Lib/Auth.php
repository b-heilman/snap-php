<?php

namespace Snap\Prototype\User\Lib;

class Auth{

	public static 
		$failureReason;

	static public function encodePassword($pwd){
		return \Snap\Lib\Core\Krypter::hashCode($pwd);
	}

	static public function passwordNeedsChanged(){
		if ( users_current_proto::loggedIn() ){
			if ( AUTH_INIT_STATUS != '' ){
				$u = Current::getUser();
				return preg_match('/^'.AUTH_INIT_STATUS.'$/', $u->info(AUTH_STATUS_FIELD));
			}else return false;
		}else return false;
	}

	static public function authenticate($login, $pwd, $mem = 0){
		eval('$pwd = '.AUTH_CLASS.'::encodePassword($pwd);');
		eval('$user = '.USER_CLASS.'::searchByLogin($login);');

		if ( $user[USER_PASSWORD] == $pwd ){
			$u = new Element( $user );

			if ( AUTH_VALID_STATUS != '' && !preg_match('/^'.AUTH_VALID_STATUS.'$/',
												$u->info(AUTH_STATUS_FIELD)) ){
				self::$failureReason = 'Invalid Status';
				
				return false;
			}else{
				return $u;
			}
		}else{
			self::$failureReason = 'Password Did Not Match';
			return false;
		}
	}
}