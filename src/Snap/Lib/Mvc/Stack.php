<?php

namespace Snap\Lib\Mvc;

class Stack
	implements \Countable, \ArrayAccess {
	
	protected 
		$stack = array();
	
	/**
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists( $offset ) {
		return $this->has( $offset );
	}
	
	/**
	 * @param mixed $offset
	 * @return mixed
	 */
	public function offsetGet( $offset ){
		return $this->get( $offset );
	}
	
	/**
	 * @param mixed $offset
	 * @param mixed $value
	 * @return bool
	 */
	public function offsetSet( $offset, $value ){
		if ( ! isset($offset)) {
			return $this->push( $value );
		}
		return $this->stack[$offset] = $value;
	}
	
	/**
	 * @param mixed $offset
	 * @return mixed
	 */
	public function offsetUnset( $offset ){
		if ( isset($this->stack[$offset]) ){
			$t = $this->stack[$offset];
			unset( $this->stack[$offset] );
		}else return null;
	}
	
	/**
	 * @param mixed $key The key to check for.
	 * @return boolean TRUE if the given key/index exists, FALSE otherwise.
	 */
	public function containsKey( $key ){
		return isset($this->stack[$key]);
	}
	
	public function count(){
		return count($this->stack);
	}
	
	public function has( $pos ){
		return isset($this->stack[$pos]);
	}
	
	public function get( $pos ){
		return $this->stack[$pos];
	}
	
	public function unshift( $data ){
		array_unshift( $this->stack, $data );
		
		return $this;
	}
	
	public function push( $data ){
		array_push( $this->stack, $data );
		
		return $this;
	}
	
	// TODO : I am brute forcing this a bit much.  Maybe I need to write my own unique algorith?
	public function add( $data, $push = true ){
		if ( is_array($data) && isset($data[0]) ){
			foreach( $data as $in ){
				if ( $push ){
					$this->push( $in );
				}else{
					$this->unshift( $in );
				}
			}
		}elseif( $push ){
			$this->push( $data );
		}else{
			$this->unshift( $data );
		}
		
		return $this;
	}
	
	public function merge( $in ){
		if ( $in instanceof Stack ){
			$this->stack = array_merge($this->stack, $in->stack);
		}elseif( is_array($in) ){
			$this->add( $in );
		}elseif( $in instanceof \Snap\Lib\Core\Arrayable ){
			$this->add( $in->toArray() );
		}
		
		return $this;
	}
}