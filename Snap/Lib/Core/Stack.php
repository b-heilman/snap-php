<?php

namespace Snap\Lib\Core;

use \Snap\Node;

class Stack {

	protected 
		$content, 
		$_orig;
	
	public function __construct(){
		$this->content = array();
	}
	
	protected function _add( Token $in, $place ){}
	
	public function add( $in, $back = true ){
		if ( $back ){
			array_push( $this->content, $in );
		}else{
			array_unshift( $this->content, $in );
		}
		
		if ( $in instanceof Token ){
			$this->_add( $in, ($back ? count($this->content) - 1 : 0) );
		}
		
		return $in;
	}
	
	public function addAt( $in, $where ){
		array_splice($this->content, $where, 0, array($in) );
		
		if ( $in instanceof snap_token ){
			$this->_add( $in, $where );
		}
		
		return $in;
	}
	
	public function addAgainst( $in, $target, $offset ){
		$key = array_search($target, $this->content);

		if ( $key === 0 ){
			return $this->add( $in, false );
		}elseif ( $key ){
	  		return $this->addAt( $in, $key + $offset );
	  	}else{
	  		return $this->add( $in );
	  	}
	}
	
	public function join( Stack $other ){
		$this->content = array_merge( $this->content, $other->content );
	}
	
	public function addAll( Stack $other ){
		foreach( $other->content as $el ){
			$this->add( $el );
		}
	}
	
	protected function _remove( Node\Snapable $in ){
		unset( $in );
	}
	
	public function remove( $in ){
	//	throw new \Exception('blah');
		$dex = array_search($in, $this->content, true);
		
		if ( $dex !== false ){
			$this->removeAt( $dex );
		}
	}
	
	public function removeAt( $where ){
		$res = array_splice($this->content, $where, 1);
		$this->_remove( $res[0] );
	}
	
	public function clear(){
		$debug = debug_backtrace();
		
		while ( !empty($this->content) ){
			$node = array_pop( $this->content );
			
			if ( $node instanceof Node\Snapping ){
				$this->_remove( $node );
			}
		}
	}
	
	public function get( $where ){
		return ( isset($this->content[$where]) ? $this->content[$where] : null );
	}
	
	public function count(){
		return count($this->content);
	}
	
	public function walk( $func ){
		foreach( $this->content as $el ){
			$func( $el );
		}
	}
	
	public function first( $func ){
		if ( count($this->content) > 0 ){
			$func( $this->content[0] );
		}
	}
	
	public function last( $func ){
		$c = count($this->content);
		
		if ( $c > 0 ){
			$func( $this->content[$c - 1] );
		}
	}
	
	public function __toString(){
		$res = '';
		
		foreach( $this->content as $token ){
			$res .= $token;
		}
		
		return $res;
	}
}