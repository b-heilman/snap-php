<?php

namespace Snap\Lib\Template\Token;

class Booled extends \Snap\Lib\Template\Token\Collection {

	protected 
		$true, 
		$false;
	
	public function __construct( $content ){
		$query = strpos($content, '?', 0);
		$split = strpos($content, ':', $query+1);

		while( $content{$split + 1} == ':' ){
			$split = strpos($content, ':', $split+2);
		}
		
		parent::__construct( substr($content, 0, $query) );
		
		$q = $query + 1;
		
		$this->true = substr($content, $query + 1, $split - $q);
		$this->false = substr($content, $split + 1);
		
		$this->active = null;
	}
	
	protected function activate(){
		if ( $this->active == null ){
			$query_string = $this->replaceVariables( $this->content );
			
			eval("\$test = isset($query_string);"); // how can I test something complicated?
	
			if ( $test )
				eval("\$bool = ($query_string);");
			else
				$bool = false;

			$translator = new template_translator( $this->getVars() );
			$translator->translate( $bool?$this->true:$this->false );
			
			$this->active = $translator->getStack();
		}
	}
}