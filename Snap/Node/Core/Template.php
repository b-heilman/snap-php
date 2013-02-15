<?php

// TODO this should be called \Snap\Node\Core\Template
namespace Snap\Node\Core;

use 
	\Snap\Lib\Core\Bootstrap;

abstract class Template extends Block {
	
	private
		$templateVariables = array();
	
 	protected 
 		$path, 
 		$unwrapped, 
 		$translation,
 		$delayed = false,
 		$translating = false,
 		$deferTemplate = null;
 	
 	public function __construct( $settings = array() ){
 		parent::__construct( $settings );
 	
 		$this->addTemplateContent( $this->makeTemplateContent() );
 	}
 	
 	protected function parseSettings( $settings = array() ){
 		$this->path = isset($settings['template']) 
			? $settings['template'] : $this->getClassTemplate( get_class($this) );
 		
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
	
 	protected function makeTemplateContent(){
 		return array();
 	}
 	
	private function addTemplateContent( $content ){
 		foreach( $content as $name => $component ){
 			if ( $component instanceof \Snap\Control\Feed ){
 				$this->addTemplateFeed( $component, $name );
 			}elseif( $component instanceof \Snap\Node\Core\Snapable ){
 				$this->addTemplateNode( $component, $name );
 			}else{
 				$this->addTemplateVariable( $component, $name );
 			}
 		}
 	}
 	
 	private function addTemplateFeed( \Snap\Control\Feed $in, $name ){
 		$this->append( $in );
 	}
 	
 	private function addTemplateNode( \Snap\Node\Core\Snapable $in, $name ){
 		 $this->templateVariables[$name] = $in;
 	}
 	
 	private function addTemplateVariable( $in, $name ){
 		$this->templateVariables[$name] = $in;
 	}
 	
 	protected function getTemplateVariables(){
 		return $this->templateVariables;
 	}
 	
 	public function setTemplateData( $data ){
 		$this->addTemplateContent( $data );
 	}
 	
 	public function setTranslationeData( $data ){
 		if ( $this->translation ) {
 			$this->translation->addData( $data );
 		}
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
 			$this->processTemplateString( $this->getTemplateContent() );
 		}catch( Exception $ex ){
 			throw new \Exception(
 				"==== ".get_class($this)." - processTemplate ====\n"
 				. "\n{$ex->getFile()}: {$ex->getLine()}\n----"
 				. "\n{$ex->getMessage()}\n++++\n".$ex->getTraceAsString()
 			);
 		}
 	}
	
 	protected function getTemplate( $template ){
 		if ( $template instanceof \Snap\Node\Core\Template ){
 			return $this->getClassTemplate( get_class($template) );
 		}
 		
 		$template = str_replace( '\\', '/', $template );
 		
 		if ( $template{0} == '/' ){
 			$path = Bootstrap::testFile( $template );
 		}else{
 			$class = get_class($this);
	 		$pos = strpos( $class , 'Node' );
			
			if ( $pos === false ){
				$path = null;
			}else{
				$path = Bootstrap::testFile( substr($class,0,$pos).'Template/'.$template );
			}
 		}
 		
 		return $path;
 	}
 	
 	protected function getClassTemplate( $class ){
 		do {
 			$path = Bootstrap::testFile( Bootstrap::getTemplateFile($class) );
 			$class = get_parent_class( $class );
 			// don't allow this to search past template
 		}while( $class && !$path && $class != 'Snap\Node\Core\Template' );
 		
 		return $path;
 	}
 	
 	protected function includeParentTemplate(){
 		$path = $this->getClassTemplate( get_parent_class($this) );
 		
 		if ( $path ){
 			$this->loadTemplate( $path );
 		}
 	}
 	
 	/**
 	 * A hook for when the template is being processed.  If someone called ->append() in the template, this will gracefully
 	 * add the node to the stack, and process the previous string content.
 	 * @param \Snap\Node\Core\Snapable $in
 	 * 
 	 * (non-PHPdoc)
 	 * @see \Snap\Node\Core\Block::pend()
 	 */
 	protected function pend( \Snap\Node\Core\Snapable $in ){
 		if ( $this->translating ){
 			$content = ob_get_contents();
 			
 			ob_end_clean();
	 		
	 		$this->processTemplateString($content);
	 		
	 		ob_start();
 		}
 		
 		return parent::pend($in);
 	}
 	
 	protected function getTemplateContent(){
 		if ( $this->path == '' ){
 			throw new \Exception( 'Path is blank for '.get_class($this) );
 		}
 		
 		$this->translating = true;
 		
 		ob_start();
 		
 		$this->loadTemplate( $this->path );
 		
 		$__content = ob_get_contents();
 		ob_end_clean();
 		
 		$this->translating = false;
 		
 		return $__content;
 	}
 	
 	// __ is used to avoid collisions here, not sure if better way?
 	protected function loadTemplate( $__template ){
 		// decode the variables for local use of the included function
 		$__vars = $this->getTemplateVariables();
 		foreach( $__vars as $__var => $__val ){
 			${$__var} = $__val;
 		}
 		
 		// call the template
 		include $__template;
 	}
	
	public function build(){
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