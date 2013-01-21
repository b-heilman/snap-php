<?php

namespace Snap\Lib\Node\Extension;

use 
	\Snap\Lib\Linking\Resource,
	\Snap\Node;

class Javascript extends Base
	implements \Snap\Lib\Node\Extension\Content, \Snap\Lib\Node\Extension\Links {
	
	protected 
		$content = '', 
		$links = array(), 
		$local = array(), 
		$libraries = array();
	
	protected function canHandle( Node\Core\Snapable $node ){
		return ( 
			$node instanceof Node\Core\Actionable 
			|| $node instanceof Node\Core\Actionable\Dynamic
		);
	}
	
	// TODO : Actionable is horrible, it should all be merged and the link type set by the class...
	protected function _run( Node\Core\Snapable $node ){
		if ( $node instanceof Node\Core\Actionable ){
			$actions = $node->getActions();
			
			if ( !is_array($actions) ){
				throw new \Exception(
					'Need to pass an array of type Resource\Local or Resource\Remote from '.get_class($node)
				);
			}
			
			$c = count($actions);
			
			for( $i = 0; $i < $c; $i++ ){
				$a = $actions[$i];
				
				if ( $a instanceof Resource\Local ){
					$this->links[] = $a->getLink( 'Javascript', '.js' );
				}elseif ( $a instanceof Resource\Remote ){
					$this->links[] = $a->getLink();
				}
			}	
		}

		if ( $node instanceof Node\Core\Actionable\Dynamic ){
			$this->content .= '/* '.get_class($node)." */\n".$node->getJavascript()."\n";
		}
	}
	
	public function getContent(){
		$files = array_unique( $this->local );
		$js = '';
		
		foreach( $files as $file ){
			$code = Bootstrap::loadFile($file);
			if ( $code != null ){
				$js .= "\n/* loading : $file */\n".$code."\n";
			}
		}
		
		return "<script>\n" . $js."\n".$this->content . "\n</script>";
	}
	
	public function getLinks(){
		$links = array_unique( $this->links );
		$js = '';
		
		
		foreach( $links as $link ){
			$js .= "\n<script type='text/javascript' src='$link'></script>";
		}
			
		return $js;
	}
}