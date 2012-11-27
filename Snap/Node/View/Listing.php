<?php

namespace Snap\Node\View;

abstract class Listing extends \Snap\Node\View {
	
	protected
		$childTag;
	
	public function __construct( $settings = array() ){
		if ( !isset($settings['tag']) ){
			$settings['tag'] = 'ul';
		}
		
		$this->childTag = isset($settings['childTag']) ? $settings['childTag'] : 'li';
		
		parent::__construct( $settings );
	}
	
	protected function getContent(){
 		if ( $this->path == '' ){
 			throw new \Exception( 'Path is blank for '.get_class($this) );
 		}
 		
 		$content = '';
 		$data = $this->getStreamData();
 		$c = $data->count();
 		
 		for( $i = 0; $i < $c; $i++ ){
	 		$this->translating = true;
	 		
	 		ob_start();
	 		
	 		// decode the variables for local use of the included function
	 		$vars = $this->setVariables( $data->get($i) );
	 		foreach( $vars as $var => $val ){
	 			${$var} = $val;
	 		}
	 		
	 		// call the template
	 		include $this->path;
	 		
	 		$innerContent = ob_get_contents();
	 		ob_end_clean();
	 		
	 		$this->translating = false;
	 		
	 		$content .= "<{$this->childTag}>$innerContent</{$this->childTag}>";
 		}
 		
 		return $content;
 	}
 	
 	protected function setVariables( $info = array() ){
 		return $info;
 	}
}