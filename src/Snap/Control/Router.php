<?php

namespace Snap\Control;

use
	\Snap\Lib\Control\Redirect,
	\Snap\Lib\Control\Reroute;

abstract class Router extends \Snap\Lib\Core\StdObject {
	
	protected
		$routingTable = array(),
		$redirect = null,
		$asJson,
		$isRaw;
	
	public function __construct(){
		parent::__construct();
		
		$this->asJson = isset($_GET['__asJson']);
		$this->isRaw = isset($_GET['__contentOnly']);
	}
	
	protected function duplicate( Router $in ){
		$this->routingTable = &$in->routingTable;
	}
	
	protected function addRoute( &$mount, $dex, $action ){
		if ( $dex{0} == '/' ){
			if ( $dex == '/' ){
				$mount[$dex] = $action;
			}else{
				if ( !isset($mount['/settings']) ){
					$mount['/settings'] = array();
				}
				$mount['/settings'][substr($dex,1)] = $action;
			}
		}else{
			$mount = &$mount[ $dex ];
			
			if ( is_array($action) ){
				foreach( $action as $route => $act ){
					if ( $route{0} != '/' && !isset($mount[$route]) ){
						$mount[$route] = array();
					}
					
					$this->addRoute( $mount, $route, $act );
				}
			}else{
				$mount['/'] = $action;
			}
		}
	}
	
	public function setHome( $action ){
		$this->addRoute($this->routingTable, '/', $action);
	}
	
	public function addRoutes( array $routing ){
		foreach( $routing as $route => $action ){
			$mount = &$this->routingTable;
			
			if ( $route{0} == '/' ){
				$this->addRoute( $mount, $route, $action );
			}elseif ( is_array($action) ){
				$this->addRoute( $mount, $route, $action );
			}else{
				$indexs = explode( '/', $route );	
				$lastMount = &$mount;
				$lastDex = '/'; // this should never happen
				
				while( !empty($indexs) ){
					$dex = array_shift( $indexs );
					if ( !isset($mount[$dex]) ){
						$mount[$dex] = array();
					}
					
					$lastMount = &$mount;
					$lastDex = $dex;
					
					$mount = &$mount[$dex];
				}
				
				$this->addRoute( $lastMount, $lastDex, $action );
			}
		}
	}
	
	protected function findRoute( $path ){
		$mount = $this->routingTable;
		$settings = array();
		$back = array();
		
		$lastAction = $this->routingTable['/'];
		$lastPath = $path;
		$lastBack = $back;
		$lastSettings = array();
		
		while( !empty($path) ){
			$p = array_shift($path);
				
			if ( isset($mount[$p]) ){
				$mount = $mount[$p];
		
				if ( isset($mount['/settings']) ){
					$settings = $mount['/settings'] + $settings; // build the settings
				}
				
				if ( isset($mount['/']) ){
					$lastSettings = $settings;
					$lastPath = $path;
					$lastBack = $back;
					$lastAction = $mount['/'];
				}
				
				$back[] = $p;
			}else{
				$path = null;	
			}
		}
		
		return array( 
			'settings' => $lastSettings,
			'action'   => $lastAction, 
			'back'     => $lastBack, 
			'info'     => $lastPath 
		);
	}
	
	protected function translateRoute( $routeInfo ){
		$action   = $routeInfo['action'];
		$back     = $routeInfo['back']; 
		$info     = $routeInfo['info'];
		$settings = $routeInfo['settings'];
		$content  = null;
		
		if ( is_callable($action) ){
			$content = $action( $info );
		}elseif( $action instanceof \Snap\Node\Core\Snapable ){
			$content = $action;
		}else{
			$content = $action;
		}
		
		if ( $content instanceof \Snap\Node\Core\Page ){
			$page = $content;
		}else{
			$page = null;
			
			if ( isset($settings['page']) ){
				$page = $settings['page'];
			}
			
			if( $page instanceof \Snap\Node\Core\Page ){
				// nothing to do
			}elseif ( $page == null ){
				$page = new \Snap\Node\Page\Basic();
			}elseif ( is_callable($page) ){
				$page = $page();
			}else{
				// it should be a string
				$page = new $page();
			}
			
			if( $content instanceof \Snap\Node\Core\Snapable ){
				$page->setTemplateData(array(
					'content' => $content 
				));
			}elseif( is_string($content) ){
				if ( class_exists($content) ){
					$page->setTemplateData(array(
						'content' => new $content()
					));
				}else{
					$page->setTemplateData(array(
						'content' => new \Snap\Node\Core\Text( $content )
					));
				}
			}elseif( is_array($content) ){
				$page->setViewData( $content );
			}elseif( !empty($back) ){
				return $this->translateRoute( $this->findRoute( $back ) );
			}else return null;
		}
		
		return array( 
			'settings' => $settings,
			'action'   => $page,
			'back'     => $back, 
			'info'     => $info 
		);
	}
	
	protected function loadHeaders( $ctype ){
		switch ( $ctype ){
			case 'htm' :
			case 'html' :
				header('Content-type: text/html');
				break;
				
			case 'json' : 
				if (isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
    				header('Content-type: application/json');
				} else {
					header('Content-type: text/plain');
				}
				break;
				
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
	
	protected function respond( $data ){
		\Snap\Lib\Core\Session::save();
		
		echo $this->asJson ? $this->makeJson( $data ) : $this->makeHtml($data);
		
		$em = \Snap\Model\Doctrine::getEntityManager();
		if ( $em ){
			try {
				$em->flush();
			}catch( \Exception $ex ){
				// TODO : make a global way to handle exceptions and logging
				// TODO : there has to be a better way to do this than... this
				$this->logError( $ex );
			}
		}
	}
	
	abstract protected function loadRoutes();
	
	protected function makeJson( $response ){
		$this->loadHeaders( 'json' );
	
		$response['request'] = static::$pageRequest;
		$response['redirect'] = $this->redirect;
	
		return json_encode( $response );
	}
	
	protected function makeHtml( $response ){
		if ( !is_null($this->redirect) ){
			header( 'Location: '.static::$pageRequest.'/'.$this->redirect ) ;
		}elseif ( $this->isRaw ){
			return $response['content'];
		}else{
			$this->loadHeaders( 'htm' );
			
			$js = '';
			if ( isset($response['js']) ){
				foreach( $response['js'] as $link ){
					if ( $link != '' ){ /* TODO : where is this coming from ? */
						$js .= "\n<script type='text/javascript' src='$link'></script>";
					}
				}
			}
			
			$css = '';
			if ( isset($response['css']) ){
				foreach( $response['css'] as $link ){
					if ( $link != '' ){
						$css .= "\n<link type='text/css' rel='stylesheet' href='$link'/>";
					}
				}
			}
			
			$title = isset($response['title']) ? $response['title'] : '';
			$meta = isset($response['meta']) ? $response['meta'] : '';
			$bodyClass = isset($response['bodyClass']) ? $response['bodyClass'] : '';
			$debug = isset($response['debug']) ? $response['debug'] : '';
			$junk = isset($response['junk']) ? $response['junk'] : '';
			$content = isset($response['content']) ? $response['content'] : '';
			$onload = isset($response['onload']) ? $response['onload'] : '';
			
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
	<body class="{$bodyClass}">
		<pre>{$debug}</pre>
		{$junk}
		{$content}
		{$onload}
	</body>
</html>
HTML;
		}
	}
	
	public function serve(){
		$this->serveRoute( static::$pageData );
	}
	
	protected function serveRoute( $route ){
		try{
			$response = null;
			
			if ( count($route) > 0 ){
				$data = $route;
				$mode = $data[0];
				$info = implode( '/', array_slice($data, 1) );
					
				$fileManager = new \Snap\Lib\File\Manager( static::$pageRequest, $mode, $info ); // populate from $_GET
					
				if ( $fileManager->getMode() ){
					$accessor = $fileManager->getAccessor();
					$this->loadHeaders( $accessor->getContentType() );
					$this->isRaw = $accessor->isRawContent();
					$response = array( 'content' => $fileManager->getContent(new \Snap\Node\Page\Basic()) );
				}
			}
			
			if ( is_null($response) ){
				// delay this to improve performance of reflective resource delivery
				$this->loadRoutes();
				$settings = $this->translateRoute( $this->findRoute($route) );
				
				if ( $settings['action'] ){
					$response = $settings['action']->serve( $this );
				}else{
					$response = array( 'content' => '<!-- Page Not Found -->' );
				}
			}
			
			$this->respond( $response );
		}catch( Reroute $reroute ){
			// TODO : this is a stop gap measure right now
			$this->serveRoute( explode('/',$reroute->getReroute()) );
		}catch( Redirect $redirect ){
			// TODO : this is a stop gap measure right now
			$this->redirect = $redirect->getRedirect();
			$this->respond( array('content' => '') );
		}catch( \Exception $ex ){
			echo '-=System Error=-';
			$this->logError( $ex );
		}
	}
}