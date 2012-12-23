<?php

namespace Snap\Lib\Blog;

use 
	\Snap\Lib\Markup,
	\Snap\Lib\Token\Prototype,
	\Snap\Lib\Template;

class Translator extends Markup\Translator {
	// TODO need to do setting data
	protected function tokenHook( Prototype $in ){
		if ( !($in->getType() == '*' || $in->getType() == '#') && strpos($in->getContent(), '{{') !== false ) {
			$translator = new Template\Translator();
 			$translator->translate( $in->getContent() );
 			
 			$stack = $translator->getStack();
 			
 			if ( $stack->count() == 1 ){
 				return new Prototype( $in->getType(), $stack->get(0) );
 			}else return new  Prototype( $in->getType(), $stack );
 		}else{
 			return $in;
 		}
 	}
}