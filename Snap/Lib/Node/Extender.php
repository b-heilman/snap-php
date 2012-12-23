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
		
		while( !empty($this->queuedNodes) ){
			$nodes = $this->queuedNodes;
			$this->queuedNodes = array();
			
			$co = count($nodes);
			
			for( $i = 0; $i < $c; ++$i ){
				$ext = $this->extensions[$i];
				
				for( $j = 0; $j < $co; ++$j ){
					$ext->addNode( $nodes[$j] );
				}
				$ext->run();
			}
		}
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
		$this->queuedNodes[] = $node;
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