<?php

namespace Snap\Lib\Token;

class Prototype {

	protected 
		$type, 
		$content;
	
	public function __construct( $type, $content ){
		$this->type = $type;
		$this->content = $content;
	}
	
	public function getType(){
		return $this->type;
	}
	
	public function getContent(){
		return $this->content;
	}
}