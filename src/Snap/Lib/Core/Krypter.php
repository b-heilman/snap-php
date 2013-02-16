<?php

namespace Snap\Lib\Core;

class Krypter {

	private static 
		$iv = null,
		$key = null;
	
	private static function load(){
		if ( self::$iv == null ){
			$ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
			self::$iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
		}
	}
	
	public static function hashCode($str){
		return hash(KRYPT_HASH, $str);
	}
		
	public static function decrypt($str){
		self::load();
		
		return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, KRYPT_KEY, 
									$str, MCRYPT_MODE_ECB, self::$iv), "\0");
	}
		
	public static function encrypt($str){
		self::load();
		
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, KRYPT_KEY, $str, MCRYPT_MODE_ECB, self::$iv);
	}
	
	public static function cookieEncrypt($str){
		return base64_encode( self::encrypt($str) );
	}
	
	public static function cookieDecrypt($str){
		return self::decrypt( base64_decode($str) );
	}
	
	public static function urlEncrypt($str){
		return urlencode( self::cookieEncrypt($str) );
	}
	
	public static function urlDecrypt($str){
		return self::cookieDecrypt( urldecode($str) );
	}
}
