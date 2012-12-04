<?php

namespace Snap\Node\Page;

use Snap\Node;

abstract class Basic extends Node\Template 
	implements Node\Page, Node\Actionable, Node\Styleable {
		
	protected 
		$title,
		$debugContent = '';
	
	public function __construct( $settings = array() ){
		ob_start();
		
		$this->title = $this->defaultTitle();
		try{
			parent::__construct( $settings );
		}catch( Exception $ex ){
			echo "\n===== __construct\n".$ex->getMessage().' : '.$ex->getFile().'('.$ex->getLine().')'."\n".$ex->getTraceAsString();
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
		
		parent::parseSettings( $settings );
	}
	
	// allow a page to just be an object, no template needed, but preferred
	protected function getContent(){
		return ( $this->path == '' ) ? '' : parent::getContent();
	}
	
	protected function build(){
		ob_start();
		
		try{
			$extender = $this->inside->getExtender();
			$extender->addNode( $this ); // Need to do this, as page will be top level and not in the extensions
			
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
		return new template_translator();
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
	
	public function serve(){
		$tmp = $this->html();
		
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
			
			$html = parent::html();
			
			$title = $this->getTitle();
			$meta = $this->getMeta();
			
			$javascript = $extender->find('\Snap\Lib\Node\Extension\Javascript');
			$javascript = $javascript[0];
			
			$jsLinks    = $javascript->getLinks();
			$jsContent  = $javascript->getContent();
			
			$css = $extender->find('\Snap\Lib\Node\Extension\Css');
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
 			new \Snap\Lib\Linking\Resource\Local('/jquery.min.js'),
 			new \Snap\Lib\Linking\Resource\Local('/jquery-ui.min.js')
 		);
 	}
 	
 	public function getStyles(){
 		return array(
 				new \Snap\Lib\Linking\Resource\Local('/reset.css'),
 				new \Snap\Lib\Linking\Resource\Local( $this )
 		);
 	}
}