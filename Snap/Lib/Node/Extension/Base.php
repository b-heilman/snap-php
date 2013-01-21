<?php

namespace Snap\Lib\Node\Extension;

use \Snap\Node\Core\Snapable;

abstract class Base 
	implements \Snap\Lib\Node\Extension {
	
	static private 
		$instances;
		
	protected 
		$nodes;
	
	static public function getInstance(){
		$class = get_called_class();
		
		if ( !isset(self::$instances[$class]) ){
			self::$instances[$class] = new $class();
		}
		
		return self::$instances[$class];
	}
	
	protected function __construct(){
		$this->nodes = array();
	}
	
	public function addNode( Snapable $node ){
		if ( $this->canHandle($node) ){
			$this->nodes[] = $node;
		}
	}
	
	public function removeNode( Snapable $node ){
		$dex = array_search( $node, $this->nodes, true );
		if ( $dex !== false ){
			array_splice($this->nodes, $dex, 1);
		}
	}
	
	public function isEmpty(){
		return empty( $this->nodes );
	}
	
	public function clear(){
		$this->nodes = array();
	}
	
	public function run(){
		while( !empty($this->nodes) ){
			$this->_run( array_shift($this->nodes) );
		}
	}
	
	abstract protected function canHandle( Snapable $node );
	
	abstract protected function _run( Snapable $node );
}