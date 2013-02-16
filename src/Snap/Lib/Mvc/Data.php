<?php

namespace Snap\Lib\Mvc;

class Data {
	
	protected 
		$globals, 
		$variables, 
		$stack;
	
	public function __construct( $data = null ){
		$this->init( $data );
	}
	
	protected function init( $data = null, $vars = null ){
		$this->globals = new Globals();
		
		$this->variables = new Variables();
		if ( $vars ){
			$this->variables->set( $vars );
		}
		
		$this->stack = new Stack();
		if ( $data ){
			$this->stack->add($data);
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
	
	public function has( $pos ){
		return $this->stack->has( $pos );
	}
	
	public function count(){
		return $this->stack->count();
	}
	
	public function add( $data, $push = true ){
		$this->stack->add( $data, $push );
		
		return $this;
	}
	
	public function unshift( $data ){
		$this->stack->unshift( $data );
		
		return $this;
	}
	
	public function push( $data ){
		$this->stack->push( $data );
		
		return $this;
	}
	
	public function get( $pos ){
		if ( $this->has($pos) ){
			$t = $this->stack->get($pos);
			if ( is_array($t) ){
				return $t + $this->globals->toArray();
			}else{
				return $t;
			}
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
	
	public function merge( $in ){
		if ( $in instanceof Data ){
			$this->variables->merge( $in->variables );
			$this->stack->merge( $in->stack );
			// TODO : merge the globals...
		}elseif ( is_array($in) ){
			if ( isset($in[0]) ){
				$this->stack->merge($in);
			}else{
				$this->stack->add( $in );
			}
		}elseif( $in instanceof snap_arrayable ){
			$this->merge( $in->toArray() );
		}
	}
	
	public function makeUnique( $hashValueFunction ){
		$this->stack->makeUnique($hashValueFunction);
	}
}