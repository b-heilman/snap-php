<?php

namespace Snap\Lib\Markup;

class Tokenizer implements \Snap\Lib\Core\Tokenizer{

	protected 
		$content;
	
	public function __construct( $content ){
		$content = explode("\n", $content);
		
		$c = count($content);
		for( $i = 0; $i < $c; $i++ ){
			$temp = trim($content[$i]);
			if ( !$temp ){
				array_splice( $content, $i, 1 );
				
				$c--;
				$i--;
			}else{
				$content[$i] = $temp;
			}
		}
		
		$this->content = $content;
	}
	
	public function hasNext(){
		return !empty( $this->content );
	}
	
	public function getNext(){
		$next = array_shift($this->content);
		
		if ( isset($next{0}) ){
			$action = $next{0};
			
			if ( $action == '*' || $action == '#' ){
				$cmd = substr( $next,1 );
					
				$next = array_shift($this->content);
				while( $next && $next{0} == $action ){
					$cmd .= "\n".substr( $next,1 );
					
					if ( empty($this->content) ){
						$next = false;
					}else{
						$next = array_shift($this->content);
					}
				}
				
				if ( $next ){
					array_unshift($this->content, $next);
				}
			}elseif( strlen($next) == 0 ){
				$action = '=';
				$cmd = new \Snap\Node\Simple( array('tag' => 'br') );
			}else{
				$action = '';
				$cmd = $next;
			}
		
			$proto = new \Snap\Lib\Token\Prototype( $action, $cmd );
		}else{
			$proto = new \Snap\Lib\Token\Prototype( '', '' );
		}
		
		return $proto;
	}
}