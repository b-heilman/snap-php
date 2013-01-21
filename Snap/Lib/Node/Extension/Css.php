<?php

namespace Snap\Lib\Node\Extension;

use 
	\Snap\Lib\Linking\Resource,
	\Snap\Node;

class Css extends Base
	implements Content, Links {
	
	protected 
		$content = '', 
		$links = array(), 
		$local = array();

	protected function canHandle( Node\Core\Snapable $node ){
		return ( $node instanceof Node\Core\Styleable );
	}

	protected function _run( Node\Core\Snapable $node ){
		if ( $node instanceof Node\Core\Styleable ){
			$actions = $node->getStyles();
			
			if ( !is_array($actions) ){
				throw new \Exception(
					'Need to pass an array of type Resource\Local or Resource\Remote from '.get_class($node)
				);
			}
			
			$c = count($actions);
			
			for( $i = 0; $i < $c; $i++ ){
				$a = $actions[$i];
				
				if ( $a instanceof Resource\Local ){
					$this->links[] = $a->getLink( 'Css', '.css' );
				}elseif ( $a instanceof Resource\Remote ){
					$this->links[] = $a->getLink();
				}
			}	
		}
	}

	public function getContent(){
		/*
		$files = array_unique( $this->local );
		$css = '';
		
		foreach( $files as $file ){
			$code = Bootstrap::loadFile($file);
			if ( $code != null ){
				$css .= "\n/* loading : $file *//*\n".$code."\n";
			}
		}

		return "\n<style>\n".$css."\n/* content *//*\n".$this->content."\n</style>\n";
		*/
		
		return '';
	}

	public function getLinks(){
		$css = '';
		foreach( $this->links as $link ){
			$css .= "\n<link type='text/css' rel='stylesheet' href='$link'/>";
		}
		
		return $css."\n";
	}
}