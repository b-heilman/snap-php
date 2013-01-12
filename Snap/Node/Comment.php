<?php

namespace Snap\Node;

class Comment implements \Snap\Node\Snapable {

	protected 
		$stager, 
		$id = null,
		$page = null, 
		$dead = false, 
		$parent = null, 
		$comment = null,
		$extender = null;

	public function __construct( $settings = array() ){
		$this->parseSettings($settings);
	}

	protected function parseSettings( $settings ){
		$this->comment = isset($settings['comment']) ? $settings['comment'] : null;
	}
	
	public static function getSettings(){
		return array(
			'comment' => 'The content of the comment'
		);
	}
	
	public function setExtender( \Snap\Lib\Node\Extender $extender ){
		$this->extender = $extender;
	}
	public function hasExtender(){
		return $this->extender != null;
	}
	
	public function setPage( \Snap\Node\Page $page ){
		$this->page = $page;
	}
	
	public function kill(){
		$this->dead = true;	
	}
	
	public function clear(){
		$this->comment = '';
	}
	
	public function html(){
		return ( $this->dead ) ? '' : "<!-- {$this->inner()} -->";
	}
	
	// For now it's just a copy of render, but it's supposed to be all of the inner HTML
	public function inner(){
		return $this->comment;
    }

	public function toString(){
		return $this->html();
	}
	
	public function __toString(){
		return $this->toString();
	}
	
	public function hasId(){
		return !is_null($this->id);
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function setParent( \Snap\Node\Snapping $parent ){
		$this->parent = $parent;
	}
	
	public function getParent(){
		return $this->parent;
	}
	
	public function hasParent(){
		return !is_null($this->parent);
	}
	
	public function makeClone(){
		return $this;
	}
	
	public function removeFromParent(){
    	if ( !is_null($this->parent) ){
    		if ( is_array($this->parent) ){
    			foreach( $this->parent as $parent ){
    				$parent->remove( $this );
    			}
    		}else{
    			$this->parent->remove( $this );
    		}
    	}
    }

    public function closest( $class ){
    	$parent = $this->parent;
    	
    	while( $parent != null && !($parent instanceof $class) ){
    		$parent = $parent->parent;
    	}
    	
    	return $parent;
    }
}