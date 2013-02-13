<?php

namespace Snap\Lib\Core;

class Router extends StdObject {
	
	static protected
		$instance = null;
	
	protected
		$routingTable = array();
	
	public function __construct(){
		if ( self::$instance ){
			$this->duplicate( self::$instance );
		}else{
			$this->init();
		}
	}
	
	protected function init(){
		// add any predefined pages
	}
	
	protected function duplicate( Router $in ){
		$this->routingTable = &$in->routingTable;
	}
	
	protected function addRoute( &$mount, $dex, $action ){
		if ( $dex{0} == '/' ){
			$mount[ $dex ] = $action;
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
			
			if ( is_array($action) ){
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
		
		$lastRoute = '';
		$lastAction = $this->routingTable['/'];
		$lastPath = $path;
		$lastBack = $backPath = array();
		
		while( !empty($path) ){
			$p = array_shift($path);
				
			if ( isset($mount[$p]) ){
				$mount = $mount[$p];
		
				if ( isset($mount['/']) ){
					$lastRoute = $p;
					$lastAction = $mount['/'];
					$lastPath = $path;
					$lastBack = $backPath;
				}
			}
				
			$backPath[] = $p;
		}
		
		return array( 
			'route'  => $lastRoute,
			'action' => $lastAction, 
			'prev'   => $lastBack, 
			'info'   => $lastPath 
		);
	}
	
	protected function translateRoute( $routeInfo ){
		$action  = $routeInfo['action'];
		$prev    = $routeInfo['prev']; 
		$route   = $routeInfo['route']; 
		$info    = $routeInfo['info'];
		$content = null;
		
		if ( is_callable($action) ){
			$content = $action( $info );
		}elseif( $action instanceof \Snap\Node\Core\Snapable ){
			$content = $action;
		}else{
			$content = $action;
		}
		
		if ( $content instanceof \Snap\Node\Core\Page ){
			$content->setPathData( $info );
			$page = $content;
		}elseif( $content instanceof \Snap\Node\Core\Snapable ){
			$page = new \Snap\Node\Page\Basic();
			$page->append( $content );
		}elseif( is_string($content) ){
			$page = new \Snap\Node\Page\Basic();
			$page->write( $content );
		}elseif( !empty($prev) ){
			return $this->translateRoute( $this->findRoute( $prev ) );
		}else{
			$page = null;
		}
		
		return array( 
			'route'  => $route,
			'action' => $page,
			'prev'   => $prev, 
			'info'   => $info 
		);
	}
	
	public function serve(){
		$settings = $this->translateRoute( $this->findRoute( static::$pagePath ) );
		
		if ( $settings['action'] ){
			$rootUrl = static::$pageUrl;
			if ( count($settings['prev']) ) {
				$rootUrl .= '/'.implode( '/', $settings['prev'] );
			}
			if ( $settings['route'] ){
				$rootUrl .= '/'.$settings['route'];
			}
			
			$settings['action']->serve( $rootUrl, $settings['info'] );
		}else{
			echo '<!-- Page Not Found -->';
		}
	}
	
	static public function getInstance(){
		if ( is_null(self::$instance) ){
			$class = get_called_class();
			self::$instance = new $class();
		}
		
		return new $class( self::$instance );
	}
}