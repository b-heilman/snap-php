<?php

namespace Snap\Lib\Template;

class Translator extends \Snap\Lib\Core\Translator  {

	public function __construct( array $defaultData = array() ){
		$this->factory = $this->makeFactory();
		$this->stack = $this->makeStack( $defaultData );
	}
	
	public function addData( $data, $value='' ){
		$this->stack->addData( $data, $value );
	}
	
	protected function makeFactory(){
		return new \Snap\Lib\Template\Token\Factory();
	}
	
	protected function makeTokenizer( $content ){
		return new \Snap\Lib\Template\Tokenizer( $content );
	}
	
	protected function makeStack( array $data = array() ){
		return new \Snap\Lib\Template\Stack($data);
	}
	
	public function __clone(){
		$this->stack = clone $this->stack;
	}
}