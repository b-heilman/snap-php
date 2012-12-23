<?php

namespace Snap\Lib\Mvc;

class Variables 
	implements \Snap\Lib\Core\Arrayable {
	
	protected 
		$variables = array();
	
	public function bind( $key, &$value ){
		$this->variables[$key] = &$value;
		
		return $this;
	}
	
	public function set( $var, $value = null ){
		if ( $var instanceof \Snap\Lib\Mvc\Variables || is_array($var) ){
			$this->merge( $var );
		}else{
			$this->variables[$var] = $value;
		}
		
		return $this;
	}
	
	public function merge( $in ){
		if ( $in instanceof \Snap\Lib\Mvc\Variables ){
			$this->variables = $in->variables + $this->variables;
		}else{
			$this->variables = $in + $this->variables;
		}
		
		return $this;
	}
	
	public function get( $var ){
		return ( $this->has($var) ? $this->variables[$var] : null );
	}
	
	public function has( $var ){
		return isset( $this->variables[$var] );
	}
	
	public function count(){
		return count( $this->variables );
	}
	
	public function toArray(){
		return $this->variables;
	}
}