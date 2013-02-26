<?php

namespace Snap\Lib\Mvc\Data;

class Collection 
	implements \Snap\Lib\Mvc\Data {
	
	protected 
		$variables, 
		$data;
	
	public function __construct( $data = null, $vars = null ){
		$this->init( $data, $vars );
	}
	
	protected function init( $data = null, $vars = null ){
		$this->variables = new \Snap\Lib\Mvc\Variables();
		if ( $vars ){
			$this->variables->set( $vars );
		}
		
		$ex = new \Exception();
		
		if ( $data instanceof Collection ){
			$this->data = $data->data;
			$this->variables = $data->variables;
		}elseif ( $data instanceof \ArrayAccess && $data instanceof \Countable ){
			$this->data = $data;
		}else{
			$this->data = new \Snap\Lib\Mvc\Stack();
			if ( $data ){
				$this->data->add( $data );
			}
		}
	}
	
	public function bind( $key, &$value ) {
		$this->variables->bind( $key, $value );
		
		return $this;
	}
	
	public function setVar( $var, $value = null ){
		$this->variables->set( $var, $value );
		
		return $this;
	}
	
	public function hasVar( $var ){
		return $this->variables->has( $var );
	}
	
	public function getVar( $var ){
		return $this->variables->get( $var );
	}
	
	public function count(){
		return $this->data->count();
	}
	
	public function has( $pos ){
		return isset($this->data[$pos]);
	}
	
	public function add( $data ){
		$this->data[] = $data;
		return $this;
	}
	
	public function get( $pos ){
		if ( isset($this->data[$pos]) ){
			return $this->data[ $pos ];
		}else{
			return null;
		}
	}
	
	public function getPrimary(){
		if ( $this->hasVar('active') ){
			return $this->get( $this->getVar('active') );
		}else{
			return $this->get( 0 );
		}
	}
	
	public function merge( \Snap\Lib\Mvc\Data $in ){
		$this->variables->merge( $in->variables );
		for( $i = 0, $c = $in->data->count(); $i < $c; $i++ ){
			$this->add( $in->data[$i] );
		}
	}
	
	public function makeUnique( $hashValueFunction ){
		$tmp = array();
		
		for( $i = 0, $c = $this->data->count(); $i < $c; ++$i ){
			$key = $hashValueFunction($this->data[$i]);
			if ( !isset($tmp[$key]) ){
				$tmp[$key] = $this->data[$i];
			}
		}
		
		$this->stack = array_values($tmp);
	}
}