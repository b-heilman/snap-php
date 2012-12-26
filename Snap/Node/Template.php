<?php

// TODO this should be called \Snap\Node\Template
namespace Snap\Node;

use 
	\Snap\Lib\Core\Bootstrap;

abstract class Template extends Block {
	
 	protected 
 		$unwrapped, 
 		$translation,
 		$translating = false,
 		$path, 
 		$delayed = false,
 		$deferTemplate = null;
 	
 	protected function parseSettings( $settings = array() ){
 		$this->path = isset($settings['template']) 
			? $settings['template'] : $this->getTemplate( get_class($this) );
 		
 		if ( isset($settings['data']) && !$settings['data'] ){
			$this->delayed = true;
		}
		
		if ( isset($settings['adminMode']) && $settings['adminMode'] ){
			$this->deferTemplate = function(){
				return \Snap\Prototype\User\Lib\Current::isAdmin();
			};
		}
		
		if ( isset($settings['deferTemplate']) ){ // allows for anonymous function
			$this->deferTemplate = $settings['deferTemplate'];
		}
		
		$this->unwrapped = isset($settings['unwrapped']) ? $settings['unwrapped'] : false;
 		
 		$this->translation = isset($settings['translator'])
 			? $settings['translator'] : new \Snap\Lib\Template\Translator();
 			
 		if ( isset($settings['data']) ){
			if ( $settings['data'] ){
				$this->setData( $settings['data'] );
			}
		}
		
 		parent::parseSettings( $settings );
 	}
	
 	/**
 	 * used to translate and add string content onto the stack.  If a translator is defined, it will translate the string content
 	 * in a template and add any nodes to the stack, otherwise it will simply add the string directly onto the stack.
 	 * @param string $content
 	 */
	protected function processTemplateString( $content ){
		$this->translation->translate( $content );
 		
 		$this->inside->addAll( $this->translation->getStack() );
 		$this->translation->clear();
 	}
 	
 	/**
 	 * Begins the translating of a template located at $this->path. 
 	 */
 	protected function processTemplate(){
 		try {
 			$this->deferTemplate = null; // acts as a lock, saves on a boolean
 			$this->processTemplateString( $this->getContent() );
 		}catch( Exception $ex ){
 			throw new \Exception(
 				"==== ".get_class($this)." - processTemplate ====\n"
 				. "\n{$ex->getFile()}: {$ex->getLine()}\n----"
 				. "\n{$ex->getMessage()}\n++++\n".$ex->getTraceAsString()
 			);
 		}
 	}
	
 	protected function getTemplate( $class ){
 		do {
 			$path = Bootstrap::testFile( Bootstrap::getTemplateFile($class) );
 			$class = get_parent_class( $class );
 		}while( $class && !$path );
 		
 		return $path;
 	}
 	
 	protected function includeParentTemplate(){
 		$path = $this->getTemplate( get_parent_class($this) );
 		
 		if ( $path ){
 			include( $path );
 		}
 	}
 	
 	/**
 	 * A hook for when the template is being processed.  If someone called ->append() in the template, this will gracefully
 	 * add the node to the stack, and process the previous string content.
 	 * @param \Snap\Node\Snapable $in
 	 * 
 	 * (non-PHPdoc)
 	 * @see \Snap\Node\Block::pend()
 	 */
 	protected function pend( \Snap\Node\Snapable $in ){
 		if ( $this->translating ){
 			$content = ob_get_contents();
 			
 			ob_end_clean();
	 		
	 		$this->processTemplateString($content);
	 		
	 		ob_start();
 		}
 		
 		return parent::pend($in);
 	}
 	
 	// __ is used to avoid collisions here, not sure if better way?
 	protected function getContent(){
 		if ( $this->path == '' ){
 			throw new \Exception( 'Path is blank for '.get_class($this) );
 		}
 		
 		$this->translating = true;
 		
 		ob_start();
 		
 		// decode the variables for local use of the included function
 		$__vars = $this->getTemplateVariables();
 		foreach( $__vars as $__var => $__val ){
 			${$__var} = $__val;
 		}
 		
 		// call the template
 		include $this->path;
 		
 		$__content = ob_get_contents();
 		ob_end_clean();
 		
 		$this->translating = false;
 		
 		return $__content;
 	}
 	
 	protected function getTemplateVariables(){
 		return array();
 	}
 	
	protected function setData( $data ){
 		if ( $this->translation ) {
 			$this->translation->addData( $data );
		}
 	}
	
	public function setTranslationData( $data ){
		$this->setData( $data );
		
		$this->processTemplate();
	}
	
	protected function build(){
		parent::build();
		
		if ( !is_null($this->deferTemplate) ){
			$this->addClass('defer-process');
		}elseif ( !$this->delayed ){
			$this->processTemplate();
		}
	}
	
	protected function canRunTemplate(){
		$t = $this->deferTemplate;
		return is_null($t) || $t($this);
	}
	
	protected function _finalize(){
		if ( !is_null($this->deferTemplate) ){
			if ( $this->canRunTemplate() ){
				$this->processTemplate();
			}else{
				$this->addClass('reject-process');
			}
		}
		
		parent::_finalize();
	}
	
	public function html(){
 		if ( $this->dead ){
			return '';
		}else{
			$class = get_class($this);
			
			$content = '';
			if ( $this->unwrapped ) {
	 			$content .= $this->inner();
	 		}else{
	 			$inner = $this->inner();
	 			$content .= "<{$this->tag} {$this->getAttributes()}>{$inner}</{$this->tag}>";
	 		}
	 		
	 		return "<!-- $class -->".$content."<!-- end : $class -->";
		}
 	}
}