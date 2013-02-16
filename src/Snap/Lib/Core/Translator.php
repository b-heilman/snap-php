<?php

namespace Snap\Lib\Core;

use \Snap\Lib\Token\Prototype;

abstract class Translator  {

	protected 
		$factory, 
		$stack;
	
	public function __construct(){
		$this->factory = $this->makeFactory();
		$this->stack = $this->makeStack();
	}
	
	public function getStack(){
		return $this->stack;
	}
 	
 	public function addData( $data, $value = '' ){}
 	
 	protected function tokenHook( Prototype $in ){
 		return $in;
 	}
 	
 	public function clear(){
 		$this->stack->clear();
 	}
 	
	public function translate( $content ){
		$tokenizer = $this->makeTokenizer( $content );
		
		while( $tokenizer->hasNext() ){
			$next = $this->tokenHook(
				$tokenizer->getNext()
			);
			
			$token = $this->factory->make($next, get_class($this));
			
			$this->stack->add( $token );
		}
	}
	
	abstract protected function makeFactory();
	abstract protected function makeTokenizer( $content );
	abstract protected function makeStack();
}