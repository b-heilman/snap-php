<?php

namespace Snap\Node\Page;

use Snap\Node;

abstract class Basic extends Node\Template 
	implements Node\Page, Node\Actionable, Node\Styleable {
		
	protected 
		$mode,
		$title,
		$manager,
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
		
		$this->manager = new \Snap\Lib\File\Manager();
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
	
	public function setPageData( $data ){
		ob_start();
		
		$this->setTranslationData( $data );
		$this->debugContent .= ob_get_contents();

		ob_end_clean();
	}
	
	protected function loadHeaders( $ctype ){
		error_log( $ctype );
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
	
	public function serve(){
		$manager = new \Snap\Lib\File\Manager( true ); // populate from $_GET
		
		if ( $manager->getMode() ){
			$this->loadHeaders( $manager->getAccessor()->getContentType() );
			$tmp = $manager->getContent( $this );
		}else{
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
 	
 	public function getBasePath(){
 		return $this->basePath;
 	}
 	
 	public function getManager(){
 		return $this->manager;
 	}
 	
 	// TODO : these need to be removed
 	public function makeResourceLink( $resource ){
 		$manager = new \Snap\Lib\File\Manager( new \Snap\Lib\File\Accessor\Resource($resource) );
 		
 		return $manager->makeLink();
 	}
 	
 	public function makeLibraryLink( $library ){
 		$manager = new \Snap\Lib\File\Manager( new \Snap\Lib\File\Accessor\Library($library) );
 		
 		return $manager->makeLink();
 	}
 	
 	public function makeAjaxLink( $class, $data ){
 		$manager = new \Snap\Lib\File\Manager( new \Snap\Lib\File\Accessor\Ajax($class,$data) );
 		
 		return $manager->makeLink();
 	}
}