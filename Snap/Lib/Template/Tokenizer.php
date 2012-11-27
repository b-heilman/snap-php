<?php

namespace Snap\Lib\Template;

use \Snap\Lib\Token\Prototype;

class Tokenizer implements \Snap\Lib\Core\Tokenizer {

	protected 
		$content = '';
	
	public function __construct( $content ){
		$this->content = trim($content);
	}
	
	public function hasNext(){
		return $this->content != '';
	}
	
	public function getNext(){
		$this->content = ltrim($this->content);
		
		$pos = strpos($this->content, '{{');
		
		if ( $pos === 0 ){
			$next = $this->findNextClose();
			
			$action = $this->content{2};
			switch( $action ){
				case '\\':
					$id = substr($this->content, 3, $next - 5);
					$find = strpos($this->content, '{{/'.$id);
					
					// if I can't find a close, forget about it
					if ( $find === false ){
						$this->content = substr($this->content, $next);
						$proto = $this->getNext();
					}else{
						$idSize = strlen($id);
						$start = 5 + $idSize;
						
						$proto = new Prototype( $id, substr($this->content, $start , $find - $start) );
						
						$this->content = substr( $this->content, $find + $idSize + 5 );
					}
					
					break;
				
				case '=' :
				case '>' :
				case '<' :
				case '?' :
					$proto = new Prototype( $action, substr($this->content, 3, $next - 5) );
					$this->content = substr($this->content, $next);
					
					break;
					
				default :
					$proto = new Prototype( '$', substr($this->content, 2, $next - 4) );
					$this->content = substr($this->content, $next);
					
					break;
			}
		}elseif( $pos === false){
			$proto = new Prototype( '', $this->content );
			$this->content = '';
		}else{
			$fill = substr($this->content, 0, $pos);
			$this->content = substr($this->content, $pos);
			
			if ( strlen($fill) == 0 ){
				$proto = $this->getNext();
			}else{
				$proto = new Prototype( '', $fill );
			}
		}
		
		return $proto;
	}
	
	protected function findNextClose(){
		$string = $this->content;
		$l = strlen( $string );
		$found = 1;
		$i = 2;

		while( $i < $l && $found != 0 ){
			// start looking through the string for an end spot
			if ( $string{$i} == '}' ){
				if ( $string{$i+1} == '}' ){
					$found--;
				}
				$i += 2;
			}elseif ( $string{$i} == '{' ){
				if ( $string{$i+1} == '{'){
					$found++;
				}
				$i += 2;
			}else{
				$i++;
			}
		}

		return $i;
	}
}