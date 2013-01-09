<?php

namespace Snap\Node\Page;

use Snap\Node;

abstract class Basic extends Node\Template 
	implements Node\Page, Node\Actionable, Node\Styleable {
		
	protected 
		$basePath = '/',
		$mode,
		$title,
		$debugContent = '';
	
	public function __construct( $settings = array() ){
		ob_start();
		
		try{
			parent::__construct( $settings );
		}catch( Exception $ex ){
			echo "\n===== __construct\n".$ex->getMessage().' : '.$ex->getFile().'('.$ex->getLine().')'
				."\n".$ex->getTraceAsString();
		}
		
		if ( isset($_SERVER['REDIRECT_URL']) ){
			// REDIRECT_URL - PHP_SELF
			// http://localhost/test/ym/something/or/other?woot=1
			// '/test/ym/something/or/other' - '/redirect.php'
			// /ym/something/or/other
		
			$find = explode( '/', $_SERVER['PHP_SELF']  );
			$path = explode( '/', $_SERVER['REDIRECT_URL'], count($find) + 1 );
		
			$this->basePath = array_pop( $path );
		}elseif( isset($_SERVER['PATH_INFO']) ){
			// PATH_INFO
			// http://localhost/test/index.php/ym/something/or/other?woot=1
			// /ym/something/or/other
			$this->basePath = $_SERVER['PATH_INFO'];
		}else{
			$this->basePath = $_SERVER['PHP_SELF'];
		}
		
		$this->basePath .= ( strpos($this->basePath,'?') === false )
			? '?'
			: '&';
		
		$this->debugContent .= ob_get_contents();
		
		// Need to do this, as page will be top level and not in the extensions
		$extender = $this->inside->getExtender();
		$extender->addNode( $this ); 
		
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
	
	protected function takeControl( \Snap\Node\Snapable $in ){
		// Nothing should be above the page
		$in->setPage( $this );
	}
	
	// allow a page to just be an object, no template needed, but preferred
	protected function getContent(){
		return ( $this->path == '' ) ? '' : parent::getContent();
	}
	
	public function build(){
		ob_start();
		
		try{
			switch( $this->mode ){
				case 'ajax' :
					$this->ajaxBuild();
					break;
					
				default :
					$this->htmlBuild();
			}
		}catch( Exception $ex ){
			echo "==== ".get_class($this)." - build ====\n"
				. "\n{$ex->getFile()}: {$ex->getLine()}\n----"
				. "\n{$ex->getMessage()}\n++++\n".$ex->getTraceAsString();
		}
			
		$this->debugContent .= ob_get_contents();
			
		ob_end_clean();
	}
	
	protected function ajaxBuild(){
		// TODO : holy hell I should be sanitizing this
		$class = $_GET[ '__ajaxClass' ];
		$vars = json_decode( $_GET['__ajaxInit'] );
		
		$this->append( new $class( $vars ) );
	}
	
	protected function htmlBuild(){
		parent::build();
	}
	
	protected function getTranslator(){
		return new \Snap\Lib\Template\Translator();
	}
	
	abstract protected function defaultTitle();
	abstract protected function getMeta();
	
	public function setTitle( $title ){
		$this->title = $title;
	}
	
	public function setPageData( $data ){
		ob_start();
		
		$this->setTranslationData( $data );
		$this->debugContent .= ob_get_contents();

		ob_end_clean();
	}
	
	protected function loadHeaders( $file ){
		$legal = false;
		
		if ( substr($file, -2) === 'js' ){
			$legal = true;
			header('Content-type: application/javascript');
		}else{
			$ctype = substr($file, -3);
			switch ( $ctype ){
				case 'css' :
					$legal = true;
					header('Content-type: text/css');
					break;
		
				case "jpeg":
					$ctype = 'jpg';
				case "jpg" :
				case "gif" :
				case "png" :
					$legal = true;
					header('Content-type: image/'.$ctype);
					break;
			}
		}
		
		return $legal;
	}
	
	protected function serveLibrary(){
		if ( isset($_GET['__library']) ){
			$libFile = $_GET['__library'];
			
			if ( strpos(substr($libFile, 0, strlen($libFile) - 4), '..') === false ){
				$file = \Snap\Lib\Core\Bootstrap::getLibraryFile( $libFile );
				
				if ( $this->loadHeaders($file) ){
					$this->loadHeaders( $file );
					
					return file_get_contents( $file );
				}
			}
		}
		
		return '';
	}
	
	protected function serveResource(){
		if ( isset($_GET['__resource']) ){
			$resFile = $_GET['__resource'];
			
			$file = \Snap\Lib\Core\Bootstrap::testFile( $resFile );
			
			if ( $file && $this->loadHeaders($resFile) ){
				return file_get_contents( $file );
			}
		}
			
		return '';
	}
	
	public function serve(){
		$service = isset($_GET['__service']) ? $_GET['__service'] : null;
		
		switch( $service ){
			case 'library' :
				$this->mode = 'library';
				$tmp = $this->serveLibrary();
				break;
				
			case 'resource' :
				$this->mode = 'resource';
				$tmp = $this->serveResource();
				break;
				
			case 'ajax' :
				$this->mode = 'ajax';
				$tmp = $this->inner();
				break;
				
			default :
				$this->mode = 'default';
				$tmp = $this->html();
		}
		
		\Snap\Lib\Core\Session::save();
		
		echo $tmp;
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
			$extender->run();
			
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
		{$cssContent}
	</head>
	<body>
		<pre>{$this->debugContent}</pre>
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
 	
 	public function getActions(){
 		return array(
 			new \Snap\Lib\Linking\Resource\Local( $this,'/jquery.min.js'),
 			new \Snap\Lib\Linking\Resource\Local( $this,'/jquery-ui.min.js')
 		);
 	}
 	
 	public function getStyles(){
 		return array(
 			new \Snap\Lib\Linking\Resource\Local( $this,'/reset.css'),
 			new \Snap\Lib\Linking\Resource\Local( $this, $this )
 		);
 	}
 	
 	public function makeResourceLink( $resource ){
 		return $this->basePath.'__service=resource&__resource='.urlencode( $resource );
 	}
 	
 	public function makeLibraryLink( $library ){
 		return $this->basePath.'__service=library&__library='.urlencode( $library );
 	}
 	
 	public function makeAjaxLink( $class, $data ){
 		return $this->basePath.'__service=ajax&__ajaxClass='.urlencode( $class )
 			.'&__ajaxInit='.urlencode(json_encode($data));
 	}
}