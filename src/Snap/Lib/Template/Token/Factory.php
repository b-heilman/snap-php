<?php

namespace Snap\Lib\Template\Token;

class Factory implements \Snap\Lib\Token\Factory {

	public function make( \Snap\Lib\Token\Prototype $proto, $translatorClass ){
		$type = $proto->getType();
		
		switch( $type ){
			case '=' :
				$token = new \Snap\Lib\Template\Token\Value( $proto->getContent() );
				break;
				
			case '>' :
				$token = new \Snap\Lib\Template\Token\Constructor( $proto->getContent() );
				break;
				
			case '<' :
				$token = new \Snap\Lib\Template\Token\Import( $proto->getContent() );
				break;
				
			case '?' :
				$token = new \Snap\Lib\Template\Token\Booled( $proto->getContent() );
				break;
				
			case '$' :
				$token = new \Snap\Lib\Template\Token\Stringed( $proto->getContent(), true );
				break;
				
			default :
				
				if ( strlen($type) > 0 ){
					$token = new \Snap\Lib\Template\Token\Named( $proto->getContent(), $type );
				}else{
					$token = new \Snap\Lib\Template\Token\Stringed( $proto->getContent(), false );
				}
				
				break;
		}
		
		return $token;
	}
}