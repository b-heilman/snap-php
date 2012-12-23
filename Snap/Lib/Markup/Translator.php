<?php

namespace Snap\Lib\Markup;

class Translator extends \Snap\Lib\Core\Translator  {

	protected 
		$topLevel;
	
	public function __construct( $topLevel = true ){
		$this->factory = $this->makeFactory( $topLevel );
		$this->stack = $this->makeStack();
	}
	
	protected function makeFactory( $topLevel = true ){
		return new Token\Factory( $topLevel );
	}
	
	protected function makeTokenizer( $content ){
		return new Tokenizer( $content );
	}
	
	protected function makeStack(){
		return new \Snap\Lib\Core\Stack();
	}
}