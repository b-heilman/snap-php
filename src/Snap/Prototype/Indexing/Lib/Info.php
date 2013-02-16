<?php

namespace Snap\Prototype\Indexing\Lib;

class Info {

	protected 
		$parentPath,
		$target,
		$node;

	public function __construct( $target, Point $node){
		$this->parentPath = '/';
		$this->target = $target;
		$this->node = $node;
	}

	public function isCurrent(){
		return $this->node == Organizer::$currentNode;
	}

	public function addPathDir( $dir ){
		$this->parentPath = '/'.$dir.$this->parentPath;
	}

	public function getFullPath(){
		return $this->parentPath.$this->target;
	}

	public function getDisplay(){
		$display = $this->node->getLinkTitle();

		if ( !$display ){
			$display = $this->node->getPath();
		}

		return $display;
	}

	public function getSetting( $setting ){
		return $this->node->getSetting( $setting );
	}

	public function __toString(){
		return $this->getFullPath();
	}
}