<?php

namespace Snap\Lib\Node;

use \Snap\Node\Snapable;

class Extender {

	protected
		$queuedNodes = array(),
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
		$extensionStack = array();
		
		for( $i = 0; $i < $c; $i++ ){
			$extensionStack[$i] = array();
		}
		$extensionStack[$c] = array();
		
		while( !empty($this->queuedNodes) ){
			$extensionStack[0] = $this->queuedNodes;
			$this->queuedNodes = array();
			
			for( $i = 0; $i < $c; ++$i ){
				// Interate the extensions
				$nodes = $extensionStack[$i];
				$ext = $this->extensions[$i];
				
				$co = count($nodes);
				
				for( $j = 0; $j < $co; ++$j ){
					// Interate the nodes
					$ext->addNode( $nodes[$j] );
				}
				$ext->run();
				
				// shift the current nodes to the next extension
				$extensionStack[ $i ] = array();
				$extensionStack[ $i+1 ] = array_merge( $extensionStack[$i+1], $nodes );
				
				if ( !empty($this->queuedNodes) ){
					break;
				}
			}
		}
		
		unset( $extensionStack );
	}
	
	public function findExtension( $class ){
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
		if ( !$node->hasExtender() ){
			$node->setExtender( $this );
			$this->queuedNodes[] = $node;
		}
	}
	
	public function removeNode( Snapable $node ){
		$c = count($this->extensions);
		
		for( $i = 0; $i < $c; ++$i ){
			$this->extensions[$i]->removeNode( $node );
		}
		
		$where = array_search( $node, $this->queuedNodes, true );
		
		if ( $where !== false ){
			array_splice( $this->queuedNodes, $where, 1 );
		}
	}
	
	public function clear(){
		$c = count($this->extensions);
		
		for( $i = 0; $i < $c; ++$i ){
			$this->extensions[$i]->clear();
		}
		
		$this->queuedNodes = array();
	}
}