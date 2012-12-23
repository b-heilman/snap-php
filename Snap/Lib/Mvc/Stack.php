<?php

namespace Snap\Lib\Mvc;

class Stack {
	protected $stack = array();
	
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
	
	// the function hasValueFunction will return back a hash value for that row
	public function makeUnique( $hashValueFunction ){
		$tmp = array();
		
		for( $i = 0, $c = count($this->stack); $i < $c; ++$i ){
			$key = $hashValueFunction($this->stack[$i]);
			if ( !isset($tmp[$key]) ){
				$tmp[$key] = $this->stack[$i];
			}
		}
		
		$this->stack = array_values($tmp);
	}
}