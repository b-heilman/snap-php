<?php

namespace Snap\Lib\Template\Token;

class Constructor extends \Snap\Lib\Template\Token {

	public function evaluate(){
		$element = $this->content;
		
		$func = $element;
		$vars = '';
		
		if( ($pos = strpos($element, '(')) !== false ){
			$func = substr($element, 0, $pos);
			$vars = substr($element, $pos);
		}
		
		// translate the function call to clean up the instantiation
		if( strpos($func, '$') !== false ){
			eval('$func = '.$this->replaceVariables($func).';');
		}
		
		$element = $func.$this->replaceVariables($vars);
		
		try {
			eval ('$tmp = new '.$element.';');
		}catch( Exception $ex ){
			$trace = $ex->getTrace();
			
			throw new \Exception( "loading: $element\nerror: ".$ex->getMessage()."\nfrom: ".$ex->getFile().', '.$ex->getLine() );
		}
		
		return $tmp;
	}
}