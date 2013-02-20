<?php

namespace Snap\Prototype\User\Lib;

use
	\Snap\Prototype\User\Model\Doctrine\User;

class Auth{

	public 
		$failureReason;

	public function encodePassword($pwd){
		return \Snap\Lib\Core\Krypter::hashCode($pwd);
	}

	public function authenticate( User $user, $pwd, $mem = 0 ){
		if ( $user->getPassword() == $this->encodePassword($pwd) ){
			return true;
			/*
			if ( AUTH_VALID_STATUS != '' && !preg_match('/^'.AUTH_VALID_STATUS.'$/',
												$u->info(AUTH_STATUS_FIELD)) ){
				self::$failureReason = 'Invalid Status';
				
				return false;
			}else{
				return $u;
			}
			*/
		}else{
			return false;
		}
	}
}