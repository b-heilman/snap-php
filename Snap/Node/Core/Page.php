<?php

namespace Snap\Node\Core;

use Snap\Node;

abstract class Page extends Node\Core\Template {

	public 
		$manager;
	
	protected 
		$mode,
		$title,
		$basePath = '/',
		$debugContent = '';
	
	public function __construct( $settings = array() ){
		ob_start();
		
		try{
			parent::__construct( $settings );
		}catch( Exception $ex ){
			echo "\n===== __construct\n".$ex->getMessage().' : '.$ex->getFile().'('.$ex->getLine().')'
				."\n".$ex->getTraceAsString();
		}
		
		$this->debugContent .= ob_get_contents();
		
		ob_end_clean();
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
		// Nothing should be above the page, so no call to the parent::
		$in->setPage( $this );
	}
	
	// allow a page to just be an object, no template needed, but preferred
	protected function getTemplateContent(){
		return ( $this->path == '' ) ? '' : parent::getTemplateContent();
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
	
	public function setPathData( $data ){
	}
	
	public function setPageData( $data ){
		ob_start();
		
		$this->setTranslationData( $data );
		$this->debugContent .= ob_get_contents();

		ob_end_clean();
	}
	
	protected function loadHeaders( $ctype ){
		switch ( $ctype ){
			case 'js' :
				header('Content-type: application/javascript');
				break;
			case 'css' :
				header('Content-type: text/css');
				break;
	
			case "jpeg":
				$ctype = 'jpg';
			case "jpg" :
			case "gif" :
			case "png" :
				header('Content-type: image/'.$ctype);
				break;
				
			default :
				break;
		}
	}
	
	public function serve( $rootUrl = null, $path = null ){
		$tmp = null;
		
		if ( $path == null ){
			$path = static::$pagePath;
		}
		
		if ( $rootUrl == null ){
			$rootUrl = static::$pageUrl;
		}
		
		$this->manager = new \Snap\Lib\File\Manager( $rootUrl );
		error_log( 'manager defined' );
		if ( count($path) > 0 ){
			$mode = $path[0];
			$info = implode( '/', array_slice($path, 1) );
			
			$manager = new \Snap\Lib\File\Manager( $rootUrl, $mode, $info ); // populate from $_GET
			
			if ( $manager->getMode() ){
				$this->loadHeaders( $manager->getAccessor()->getContentType() );
				$tmp = $manager->getContent( $this );
			}
		}
		
		if ( is_null($tmp) ){
			$tmp = $this->html();
		}
		
		\Snap\Lib\Core\Session::save();
		
		echo $tmp;
	}
	
	public function inner(){
		if ( $this->rendered == '' ){
			// TODO : this need to go to inner...
			$extender = $this->inside->getExtender();
			$extender->run();
		}
		
		return parent::inner();
	}
	
	public function html(){
		$html = '';
		$meta = '';
		$title = '';
		$cssContent = '';
		$jsLinks = '';
		$jsContent = '';
		
		ob_start();
		try{
			// Need to do this, as page will be top level and not in the extensions
			$extender = $this->inside->getExtender();
			$extender->addNode( $this );
			
			$html = parent::html();
			
			$title = $this->getTitle();
			$meta = $this->getMeta();
			
			$javascript = $extender->findExtension('\Snap\Lib\Node\Extension\Javascript');
			$javascript = $javascript[0];
			
			$jsLinks    = $javascript->getLinks();
			$jsContent  = $javascript->getContent();
			
			$css = $extender->findExtension('\Snap\Lib\Node\Extension\Css');
			$css = $css[0];
			
			$cssContent = $css->getLinks();
			$cssContent .= $css->getContent();
			
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
			$html = 
				'<pre class="page-node">'
				. $ex->getMessage()
				. "\n---------\n"
				. $ex->getFile().': '.$ex->getLine()
				. "\n=========\n"
				. $ex->getTraceAsString()
				.'</pre>';
		}

		$junk = trim( ob_get_contents() );
		ob_end_clean();
		
		return $this->makePage( $title, $meta, $jsLinks, $jsContent, $cssContent, $html, $this->debugContent, $junk );
	}
	
	protected function makePage( $title, $meta, $jsLinks, $jsContent, $css, $html, $debug, $junk ){
		return <<<HTML
<!DOCTYPE HTML>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en'>
	<head>
		<title>{$title}</title>
	
		<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
		{$meta}
		<!-- js links -->
		{$jsLinks}
		<!-- css links -->
		{$css}
	</head>
	<body>
		<pre>{$debug}</pre>
		{$junk}
		{$html}
		{$jsContent}
	</body>
</html>
HTML;
	}
	
	protected function getTitle(){
		return $this->title;
	}
 	
 	public function getBasePath(){
 		return $this->basePath;
 	}
 	
 	public function getManager(){
 		return $this->manager;
 	}
 	
 	// TODO : these need to be removed
 	public function makeResourceLink( $resource ){
 		if ( strlen($resource) ){
	 		return $this->manager->makeLink( new \Snap\Lib\File\Accessor\Resource($resource) );
 		}else{
 			return null;
 		}
 	}
 	
 	public function makeLibraryLink( $library ){
 		if ( strlen($library) ){
	 		return $this->manager->makeLink( new \Snap\Lib\File\Accessor\Document($library) );
	 	}else{
	 		return null;
	 	}
 	}
 	
 	public function makeAjaxLink( $class, $data ){
 		return $this->manager->makeLink( new \Snap\Lib\File\Accessor\Ajax($class,$data) );
 	}
}