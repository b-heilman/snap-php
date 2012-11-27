<?php

namespace Snap\Lib\Node;

use 
	\Snap\Node,
	\Snap\Lib\Core;

class Stack extends Core\Stack {
	
	protected 
		$references = array(),
		$extender, 
		$master, 
		$canProcess = true, 
		$canFinalize = true, 
		$extending = true, 
		$buffer = array();
	
	public function __construct( Node\Snapping $master, \Snap\Lib\Node\Extender $extender ){
		$this->extender = $extender;
		$this->master = $master;
		
		parent::__construct();
	}
	
	public function add( $in, $back = true, $ref = null ){
		if ( $ref != null ){
			$this->references[$ref] = $in;
		}
		
		return parent::add($in);
	}
	
	public function addAt( $in, $where, $ref = null ){
		if ( $ref != null ){
			$this->references[$ref] = $in;
		}
		
		return parent::addAt($in, $where);
	}
	
	public function addAgainst( $in, $target, $offset, $ref = null ){
		if ( $ref != null ){
			$this->references[$ref] = $in;
		}
		
		return parent::addAgainst($in, $target, $offset);
	}
	
	public function getReference( $ref ){
		return isset($this->references[$ref]) ? $this->references[$ref] : null;
	}
	
	public function stopExtending(){
		$this->extending = false;
	}
	
	public function startExtending(){
		$this->extending = true;
		
		while( !empty($this->buffer) ){
			$this->extender->addNode( array_shift($this->buffer) );
		}
	}
	
	public function getExtender(){
		return $this->extender;
	}
	
	public function getChildClasses(){
		$res = array();
		
		foreach( $this->content as $el ){
			if ( is_object($el) ){
				$res[] = get_class($el);
			}elseif( is_string($var) ){
				$res[] = 'string';
			}
		}
		
		return $res;
	}
	// return all of the elements that are of a particular class.
	// note this is the the css class but the actual php element class
	public function getElementsByClass( $class, $blockOn = null ){
		$found = array();
		
		foreach( $this->content as $el ){
			if ( is_object($el) ){
				if ( $blockOn != null && $el instanceof $blockOn ){
					// do nothing
				}elseif ( $el instanceof $class ){
					$found[] = $el;
				}elseif( $el instanceof Node\Snapping ){
					$found = array_merge($found, $el->getElementsByClass($class) );
				}
			}
		}

		return $found;
	}
	
	
	public function render(){
		$this->extender->run();
	}
	
	protected function _add( Core\Token $node, $where ){
		if ( $node instanceof Node\Stacking ){
			if ( !($this->master instanceof Node\Stacking) ){
				throw new \Exception(
					"can not add an stacking_node node to a master that is not stacking_node node\n"
						. 'tried adding a '.get_class($node).' to a '.get_class($this->master)
				);
			}
		}
		
		if ( $node instanceof Node\Snapable ){
			$this->master->verifyControl( $node );
		}
		
		if ( $this->extending ){
			$this->extender->addNode( $node );
		}else{
			$this->buffer[] = $node;
		}
		
		parent::_add( $node, $where );
	}
	
	protected function _remove( Node\Snapable $node ){
		$this->extender->removeNode( $node );
		
		parent::_remove($node);
	}
}