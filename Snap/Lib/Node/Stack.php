<?php

namespace Snap\Lib\Node;

use 
	\Snap\Node,
	\Snap\Lib\Core;

class Stack extends Core\Stack {
	
	protected 
		$references = array(),
		$extender, 
		$master;
	
	public function __construct( Node\Core\Snapping $master, \Snap\Lib\Node\Extender $extender ){
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
				}elseif( $el instanceof Node\Core\Snapping ){
					$found = array_merge($found, $el->getElementsByClass($class) );
				}
			}
		}

		return $found;
	}
	
	
	public function render(){
	//	$this->extender->run();
	}
	
	protected function _add( Core\Token $node, $where ){
		if ( $node instanceof Node\Core\Stacking ){
			if ( !($this->master instanceof Node\Core\Stacking) ){
				throw new \Exception(
					"can not add an stacking_node node to a master that is not stacking_node node\n"
						. 'tried adding a '.get_class($node).' to a '.get_class($this->master)
				);
			}
		}
		
		if ( $node instanceof Node\Core\Snapable ){
			$this->master->verifyControl( $node );
		}
		
		$this->extender->addNode( $node );
		
		parent::_add( $node, $where );
	}
	
	protected function _remove( Node\Core\Snapable $node ){
		$this->extender->removeNode( $node );
		
		parent::_remove($node);
	}
}