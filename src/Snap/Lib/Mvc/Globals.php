<?php

namespace Snap\Lib\Mvc;

class Globals
	implements \Snap\Lib\Core\Arrayable {
	
	static protected 
		$globals = null;
	
	public function __construct(){
		if ( self::$globals == null ){
			self::$globals = array();
		}
	}
	
	public function get( $var ){
		return ( $this->has($var) ? self::$globals[$var] : null );
	}
	
	public function has( $var ){
		return isset( self::$globals[$var] );
	}
	
	public function toArray(){
		return self::$globals;
	}
}