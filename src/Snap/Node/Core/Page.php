<?php

namespace Snap\Node\Core;

use Snap\Node;

abstract class Page extends Node\Core\Template {

	public 
		$fileManager;
	
	protected 
		$mode,
		$title,
		$router,
		$basePath = '/',
		$debugContent = '',
		$contentOnly;
	
	public function __construct( $settings = array() ){
		ob_start();
		
		if ( isset($_GET['__contentOnly']) ){
			$this->contentOnly = true;
			$settings['unwrapped'] = true;
		}
		
		try{
			parent::__construct( $settings );
		}catch( Exception $ex ){
			echo "\n===== __construct\n".$ex->getMessage().' : '.$ex->getFile().'('.$ex->getLine().')'
				."\n".$ex->getTraceAsString();
		}
		
		$this->debugContent .= ob_get_contents();
		
		if ( !$this->contentOnly ){
			// Need to do this, as page will be top level and not in the extensions
			$this->inside->getExtender()->addNode( $this );
		}
		
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
	
	public function getSiteUrl( $url ){
		return static::$pageRequest.$url;
	}
	
	public function serve( $data = null, \Snap\Control\Router $router = null ){
		$tmp = null;
		
		if ( $data == null ){
			$data = static::$pageData;
		}
		
		$rootUrl = static::$pageScript;
		
		$this->router = $router;
		$this->fileManager = new \Snap\Lib\File\Manager( $rootUrl );
		
		if ( count($data) > 0 ){
			$mode = $data[0];
			$info = implode( '/', array_slice($data, 1) );
			
			$fileManager = new \Snap\Lib\File\Manager( $rootUrl, $mode, $info ); // populate from $_GET
			
			if ( $fileManager->getMode() ){
				$this->loadHeaders( $fileManager->getAccessor()->getContentType() );
				$tmp = $fileManager->getContent( $this );
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
			$extender = $this->inside->getExtender();
			
			if ( $this->contentOnly ){
				// run the build only, do not add to extensions
				$this->build(); 
			}
			
			$html = parent::html();
			
			$title = $this->getTitle();
			$meta = $this->getMeta();
			
			$javascript = $extender->findExtension('\Snap\Lib\Node\Extension\Javascript');
			$javascript = $javascript[0];
			
			$jsLinks    = $javascript->getLinks();
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
		
		if ( isset($_GET['__asJson']) ){
			return $this->makeJson( $title, $meta, $jsLinks, $jsContent, 
						$cssLinks, $html, $this->debugContent, $junk );
		}else{
			return $this->makeHtml( $title, $meta, $jsLinks, $jsContent, 
						$cssLinks, $html, $this->debugContent, $junk );
		}
	}
	
	protected function makeHtml( $title, $meta, $jsLinks, $jsContent, $cssLinks, $html, $debug, $junk ){
		if ( $this->contentOnly ){
			return $html;
		}else{
			
			$js = '';
			foreach( $jsLinks as $name => $link ){
				$l = $this->fileManager->makeLink( new \Snap\Lib\File\Accessor\Resource($link) );
				if ( $l != '' ){ /* TODO : where is this coming from ? */
					$js .= "\n<script type='text/javascript' src='$l'></script>";
				}
			}
				
			$css = '';
			foreach( $cssLinks as $link ){
				$l = $this->fileManager->makeLink( new \Snap\Lib\File\Accessor\Resource($link) );
				if ( $l != '' ){
					$css .= "\n<link type='text/css' rel='stylesheet' href='$l'/>";
				}
			}
			
			return <<<HTML
<!DOCTYPE HTML>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en'>
	<head>
		<title>{$title}</title>
	
		<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
		{$meta}
		<!-- js links -->
		{$js}
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
	}
	
	protected function makeJson( $title, $meta, $jsLinks, $jsContent, $cssLinks, $html, $debug, $junk ){
		$js = array();
		foreach( $jsLinks as $name => $link ){
			$js[] .= $this->fileManager->makeLink( new \Snap\Lib\File\Accessor\Resource($link) );
		}
		
			
		$css = array();
		foreach( $cssLinks as $link ){
			$css[] = $this->fileManager->makeLink( new \Snap\Lib\File\Accessor\Resource($link) );
		}
		
		return json_encode(array(
			'title'  => $title,
			'onload' => $jsContent,
			'css'    => $css,
			'js'     => $js,
			'html'   => $html,
			'debug'  => $debug
		));
	}
	
	protected function getTitle(){
		return $this->title;
	}
 	
 	public function getBasePath(){
 		return $this->basePath;
 	}
 	
 	public function __toString(){
 		ob_start();
 		
 		$this->serve();
 		
 		$t = ob_get_contents();
 		
 		ob_clean();
 	
 		return $t;
 	}
}