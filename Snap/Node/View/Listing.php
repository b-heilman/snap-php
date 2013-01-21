<?php

namespace Snap\Node\View;

abstract class Listing extends \Snap\Node\Core\View {
	
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
 		
 		// TODO : how to avoid the collisions???
 		$__content = '';
 		$__data = $this->getStreamData();
 		$__c = $__data->count();
 		
 		for( $__i = 0; $__i < $__c; $__i++ ){
	 		$this->translating = true;
	 		
	 		ob_start();
	 		
	 		// decode the variables for local use of the included function
	 		$__vars = $this->getTemplateVariables( $__data->get($__i) );
	 		foreach( $__vars as $__var => $__val ){
	 			${$__var} = $__val;
	 		}
	 		
	 		// call the template
	 		include $this->path;
	 		
	 		$__innerContent = ob_get_contents();
	 		ob_end_clean();
	 		
	 		$this->translating = false;
	 		
	 		$__content .= "<{$this->childTag}>$__innerContent</{$this->childTag}>";
 		}
 		
 		return $__content;
 	}
 	
 	protected function getTemplateVariables( $info = null ){
 		return $info;
 	}
}