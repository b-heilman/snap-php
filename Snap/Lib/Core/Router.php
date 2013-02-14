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
			$content->setPathData( $info );
			$page = $content;
		}else{
			$page = null;
			
			if ( $settings['page'] ){
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
				$page->setTemplateData( $content );
			}elseif( !empty($back) ){
				return $this->translateRoute( $this->findRoute( $back ) );
			}else{
				$page = null;
			}
		}
		
		return array( 
			'settings' => $settings,
			'action'   => $page,
			'back'     => $back, 
			'info'     => $info 
		);
	}
	
	public function serve(){
		$settings = $this->translateRoute( $this->findRoute(static::$pageData) );
		
		if ( $settings['action'] ){
			$settings['action']->serve( $settings['info'] );
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