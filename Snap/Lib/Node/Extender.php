<?php

namespace Snap\Lib\Node;

use \Snap\Node\Snapable;

class Extender {

	static private 
		$newNodeAdded = false;
		
	protected 
		$extensions = array(),
		$position = 0;
	
	public function addExtension( Extension $ext ){
		$this->extensions[] = $ext;
	}
	
	public function removeExtension( $pos ){
		array_slice( $this->extensions, $pos );
	}
	
	public function changeExtension( $pos, Extension $ext ){
		if ( isset($extensions[$pos]) ){
			$old = $extensions[$pos];
			$extensions[$pos] = $ext;
			
			return $old;
		}else {
			$this->addExtension($ext);
			return null;
		}
	}
	
	public function run(){
		$c = count($this->extensions);
		
		for( $i = 0; $i < $c; ++$i ){
			if ( self::$newNodeAdded ){
				self::$newNodeAdded = false;
				$i = 0;
			}
			$this->extensions[$i]->run();
		}
	}
	
	public function find( $class ){
		$ext = array();
		$c = count($this->extensions);
		
		for( $i = 0; $i < $c; ++$i ){
			if ( $this->extensions[$i] instanceof $class ){
				$ext[] = $this->extensions[$i];
			}
		}
		
		return $ext;
	}
	
	public function addNode( Snapable $node ){
		self::$newNodeAdded = true;
		
		$c = count($this->extensions);
		
		for( $i = 0; $i < $c; ++$i ){
			$this->extensions[$i]->addNode( $node );
		}
	}
	
	public function removeNode( Snapable $node ){
		$c = count($this->extensions);
		
		for( $i = 0; $i < $c; ++$i ){
			$this->extensions[$i]->removeNode( $node );
		}
	}
	
	public function clear(){
		$c = count($this->extensions);
		
		for( $i = 0; $i < $c; ++$i ){
			$this->extensions[$i]->clear();
		}
	}
}