<?php

namespace Snap\Lib\Form;

class Input {
	static protected 
		$post = null, 
		$get = null;
	
	static public function setPost( array $data ){
		self::init();
		
		self::$post += $data;
	}
	
	static public function setGet( array $data ){
		self::init();
		
		self::$get += $data;
	}
	
	static protected function init(){
		if ( is_null(self::$post) ){
			self::$post = $_POST;
			self::$get = $_GET;
		}
	}
	
	static public function saveSession(){
		$snap_session = new \Snap\Lib\Core\Session('_input');
		
		$snap_session->setVar( 'post', self::$post );
		$snap_session->setVar( 'get', self::$get );
	}
	
	// note, this overrides any input that might have come in
	static public function loadSession(){
		$snap_session = new snap_session('_input');
		
		self::$post = $snap_session->getVar( 'post' );
		self::$get = $snap_session->getVar( 'get' );
	}
	
	public function __construct(){
		self::init();
	}
	
	public function issetGet( $var ){
		return isset( self::$get[$var] );
	}
	
	public function readGet( $var ){
		return $this->_read( self::$get , $var );
	}
	
	public function issetPost( $var ){
		return isset( self::$post[$var] );
	}
	
	public function readPost( $var ){
		return $this->_read( self::$post , $var );
	}
	
	public function read( $var ){
		return $this->issetPost($var) ? $this->readPost($var) : $this->readGet($var);
	}
	
	protected function _read( $source, $var ){
		return ( isset($source[$var]) ? $source[$var] : null );
	}
}