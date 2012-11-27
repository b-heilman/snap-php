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
 		$delayed = false;
 	
 	protected function parseSettings( $settings = array() ){
 		$this->path = isset($settings['template']) 
			? $settings['template'] : $this->getTemplate( get_class( $this ) );
 		
		if ( isset($settings['data']) && !$settings['data'] ){
			$this->delayed = true;
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
 	
 	protected function getContent(){
 		if ( $this->path == '' ){
 			throw new \Exception( 'Path is blank for '.get_class($this) );
 		}
 		
 		$this->translating = true;
 		
 		ob_start();
 		
 		// decode the variables for local use of the included function
 		$vars = $this->setVariables();
 		foreach( $vars as $var => $val ){
 			${$var} = $val;
 		}
 		
 		// call the template
 		include $this->path;
 		
 		$content = ob_get_contents();
 		ob_end_clean();
 		
 		$this->translating = false;
 		
 		return $content;
 	}
 	
 	protected function setVariables(){
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
		
		if ( !$this->delayed ){
			$this->processTemplate();
		}
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