<?php

namespace Snap\Node\Core;

use Snap\Node;

abstract class Page extends Node\Core\Template 
	implements Node\Core\Actionable {

	public 
		$fileManager;
	
	protected 
		$mode,
		$extender,
		$title,
		$router,
		$basePath = '/',
		$needsAdded = true,
		$debugContent = '',
		$contentOnly;
	
	public function __construct( $settings = array() ){
		ob_start();
		
		$this->extender = $this->makeExtender();
		
		if ( isset($_GET['__contentOnly']) ){
			$this->contentOnly = true;
			$settings['unwrapped'] = true;
		}else{
			$this->extender->addNode( $this, true );
		}
		
		try{
			Block::__construct( $settings );
		}catch( Exception $ex ){
			error_log( $ex->getMessage().' - '.$ex->getFile().' : '.$ex->getLine() );
			error_log( $ex->getTraceAsString() );
		}
		
		$this->debugContent .= ob_get_contents();
		
		ob_end_clean();
	}
	
	protected function makeExtender(){
		$extender = new \Snap\Lib\Node\Extender();
	
		$extender->addExtension( \Snap\Lib\Node\Extension\Streams::getInstance() );
		$extender->addExtension( \Snap\Lib\Node\Extension\Builder::getInstance() );
		$extender->addExtension( \Snap\Lib\Node\Extension\Processor::getInstance() );
		$extender->addExtension( \Snap\Lib\Node\Extension\Javascript::getInstance() );
		$extender->addExtension( \Snap\Lib\Node\Extension\Css::getInstance() );
		$extender->addExtension( \Snap\Lib\Node\Extension\Finalizer::getInstance() );
	
		return $extender;
	}
	
	protected function parseSettings( $settings = array() ){
		$settings['tag'] = 'div';
		
		if ( isset($settings['class']) ){
			$settings['class'] .= ' page-wrapper';
		}else{
			$settings['class'] = 'page-wrapper';
		}
		
		if ( isset($settings['title']) ){
			$this->title = $settings['title'];
		}else{
			$this->title = $this->defaultTitle();
		}
		
		parent::parseSettings( $settings );
	}
	
	protected function takeControl( \Snap\Node\Core\Snapable $in ){
		$in->setPage( $this );
		$this->extender->addNode( $in );
		
		parent::takeControl( $in );
	}
	
	// allow a page to just be an object, no template needed, but preferred
	protected function getTemplateHTML(){
		return ( $this->path == '' ) ? '' : parent::getTemplateHTML();
	}
	
	public function build(){
		ob_start();
		
		try{
			parent::build();
		}catch( Exception $ex ){
			echo "==== ".get_class($this)." - build ====\n"
				. "\n{$ex->getFile()}: {$ex->getLine()}\n----"
				. "\n{$ex->getMessage()}\n++++\n".$ex->getTraceAsString();
		}
			
		$this->debugContent .= ob_get_contents();
			
		ob_end_clean();
	}
	
	protected function getTranslator(){
		return new \Snap\Lib\Template\Translator();
	}
	
	abstract protected function defaultTitle();
	abstract protected function getMeta();
	
	public function setTitle( $title ){
		$this->title = $title;
	}
	
	public function getSiteUrl( $url ){
		return static::$pageRequest.$url;
	}
	
	// TODO : this is no longer really a 'serve'
	public function serve( \Snap\Control\Router $router = null ){
		$tmp = null;
		
		$this->router = $router;
		$this->fileManager = new \Snap\Lib\File\Manager( static::$pageRequest );
		
		$this->addTemplateContent( $this->makeTemplateContent() ); // moved to here to keep from extra processing resources
		return $this->getReponse();
	}
	
	public function inner(){
		if ( $this->rendered == '' ){
			$this->extender->run();
		}
		
		return parent::inner();
	}
	
	protected function getReponse(){
		$html = '';
		$meta = '';
		$title = '';
		$cssContent = '';
		$jsLinks = '';
		$jsContent = '';
		
		ob_start();
		try{
			$extender = $this->extender;
			
			if ( $this->contentOnly ){
				// run the build only, do not add to extensions
				$this->build(); 
			}
			
			$html = $this->html();
			
			$title = $this->getTitle();
			$meta = $this->getMeta();
			
			$javascript = $extender->findExtension('\Snap\Lib\Node\Extension\Javascript');
			$javascript = $javascript[0];
			
			$jsLinks    = $javascript->getLinks();
			
			//TODO : this shouyyy
			$jsContent  = $javascript->getContent();
			
			$css = $extender->findExtension('\Snap\Lib\Node\Extension\Css');
			$css = $css[0];
			
			$cssLinks = $css->getLinks();
			
			if ( !empty(static::$logs) ){
				$jsContent .= '<script id="framework_debug">';
				
				foreach( static::$logs as $obj ){
					$jsContent .= 'console.log("'.addslashes($obj->class).'", ';
				
					if ( is_string($obj->msg) ){
						$jsContent .= '"'.str_replace(array("\n","\r"), '', addslashes($obj->msg)).'"';
					}else{
						$jsContent .= json_encode( $obj->msg );
					}
				
					$jsContent .= ');';
				}
				$jsContent .= '</script>';
			}
		} catch( Exception $ex ){
			$html = '-- No HTML --';
			error_log( $ex->getMessage().' - '.$ex->getFile().' : '.$ex->getLine() );
			error_log( $ex->getTraceAsString() );
		}

		$junk = trim( ob_get_contents() );
		ob_end_clean();
		
		return $this->makeReponse( $title, $meta, $jsLinks, $jsContent, $cssLinks, $html, $this->debugContent, $junk );
	}
	
	protected function getBodyClass(){
		return 'page';
	}
	
	protected function makeReponse( $title, $meta, $jsLinks, $jsContent, $cssLinks, $html, $debug, $junk ){
		$js = array();
		foreach( $jsLinks as $name => $link ){
			$js[] .= $this->fileManager->makeLink( new \Snap\Lib\File\Accessor\Resource($link) );
		}
	
			
		$css = array();
		foreach( $cssLinks as $link ){
			$css[] = $this->fileManager->makeLink( new \Snap\Lib\File\Accessor\Resource($link) );
		}
	
		return array(
			'bodyClass' => $this->getBodyClass(),
			'title'     => $title,
			'meta'      => $meta,
			'onload'    => $jsContent,
			'css'       => $css,
			'js'        => $js,
			'html'      => $html,
			'junk'      => $junk,
			'debug'     => $debug
		);
	}
	
	protected function getTitle(){
		return $this->title;
	}
 	
 	public function getBasePath(){
 		return $this->basePath;
 	}
 	
 	public function getActions(){
 		return array(
 			new \Snap\Lib\Linking\Resource\Local( '/jquery.min.js'),
 			new \Snap\Lib\Linking\Resource\Local( '/core.js')
 		);
 	}
 	
 	public function __toString(){
 		ob_start();
 		
 		$this->serve();
 		
 		$t = ob_get_contents();
 		
 		ob_clean();
 	
 		return $t;
 	}
}