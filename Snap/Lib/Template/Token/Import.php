<?php

namespace Snap\Lib\Template\Token;

class Import extends Collection {

	protected function activate(){
		if ( $this->active == null ){
			$element = $this->content;
		
			if ( strpos($element, '$') !== false ) {
				
				eval( "\$element = ".$this->replaceVariables($element).';' );
			}
	
			ob_start();
	
			include trim($element);
			
			$content = ob_get_contents();
	
			ob_end_clean();
			
			$vars = array();
			foreach ( $this as $key => $value ) {
				if ( is_string($key) && $key{0} == '_' && $key{1} == '_' ){
					$vars[ substr($key, 2) ] = $value;
				}
			}
			
			// now I need to build the content
			$translator = new \Snap\Lib\Template\Translator( $vars );
			$translator->translate($content);
			
			$this->active = $translator->getStack();
		}
	}
}