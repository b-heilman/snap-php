<?php

namespace Snap\Control;

use
	\Snap\Lib\Control\Redirect;

class Router extends \Snap\Lib\Core\StdObject {
	
	protected
		$routingTable = array();
	
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
	
	public function serve(){
		$this->serveRoute( static::$pageData );
	}
	
	protected function serveRoute( $route ){
		try{
			$settings = $this->translateRoute( $this->findRoute($route) );
			
			if ( $settings['action'] ){
				$settings['action']->serve( $settings['info'] );
			}else{
				echo '<!-- Page Not Found -->';
			}
		}catch( Redirect $redirect ){
			// TODO : this is a stop gap measure right now
			$this->serveRoute( explode('/',$redirect->getRedirect()) );
		}catch( \Exception $ex ){
			echo '-=System Error=-';
			error_log( $ex->getMessage().' - '.$ex->getFile().' : '.$ex->getLine() );
			error_log( $ex->getTraceAsString() );
		}
	}
}