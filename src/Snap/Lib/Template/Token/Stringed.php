<?php

namespace Snap\Lib\Template\Token;

class Stringed extends \Snap\Lib\Template\Token {

	protected 
		$translate;
	
	public function __construct( $content, $translate = true ){
		$this->translate = $translate;
		parent::__construct( $content );
	}
	
	public function evaluate(){
		$rtn = $this->content;
		
		if( $this->translate && strpos($rtn, '$') !== false ){
			eval ( "\$rtn = <<<HTML\n".$this->replaceVariables($rtn)."\nHTML\n;" );
		}
		
		return $rtn;
	}
}