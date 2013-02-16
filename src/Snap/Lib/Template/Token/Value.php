<?php

namespace Snap\Lib\Template\Token;

class Value extends \Snap\Lib\Template\Token {
	public function __construct( $content ){
		$this->content = $content = trim($content);
		$this->vars = array();
		
		$this->requiredVars[$content] = 1;
	}
	
	public function evaluate(){
		$var = '__'.$this->content;
		
		return $this->{'__'.$this->content};
	}
}