<?php

namespace Snap\Lib\Template\Token;

class Named extends \Snap\Lib\Template\Token {

	protected 
		$id;
	
	public function __construct( $content, $id ){
		$this->id = $id;
		parent::__construct( $content );
	}
	
	public function evaluate(){
		$node = new \Snap\Node\Content( array(
			'id'        => $this->id, 
			'content'   => $this->content,
			'unwrapped' => true,
			'data'      => $this->getVars()
		) );
		
		return $node;
	}
}