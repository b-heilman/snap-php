<?php

namespace Snap\Lib\Core;

class StdObject {
	
	static protected 
		$configs,
		$projectRoot,
		$pageURI,           // entire called script
		$pageRequest,       // relative reference to page, no path data included
		$pageScript = null, // direct reference to page
		$pageRoot,
		$pageData,          // any extra url data
		$srcRoot,
		$webRoot,
		$fileDocuments = array(),
		$phpLibraries = array();
	
	public function __construct(){
		static::init();
	}
	
	protected function logError( $error ){
		if ( $error instanceof \Exception ){
			$this->logError( $error->getMessage(). ' - '.$error->getFile().' : '.$error->getLine() );
			$this->logError( $error->getTraceAsString() );
		}else{
			error_log( $error );
		}
	}
	
	protected function getConfig( $path ){
		$config = new \Snap\Lib\Core\Configuration( $this, $path );
		$path = $config->__path;
		
		if ( isset(static::$configs[$path]) ){
			$config = static::$configs[$path];
		}else{
			static::$configs[$path] = $config;
		}
		
		return $config;
	}
	
	static protected function init(){
		if ( static::$pageScript == null ){
			if ( isset($_SERVER['REDIRECT_URL']) ){
				$path = explode( '/', $_SERVER['REQUEST_URI'] );
				$url = explode( '/', $_SERVER['SCRIPT_NAME'] );
				$request = array();
				
				// clean up the very last element of the path, as it might have GET data
				$check = count($path) - 1;
				$p = $path[$check];
				$pos = strpos( $p, '?' );
				if ( $pos !== false ){
					$path[$check] = substr($p, 0, $pos);
				}
				
				// build the actual request path
				while( !empty($url) ){
					if ( strcmp($path[0], array_shift($url)) === 0 ){
						$request[] = array_shift($path);
					}
				}
				
				// this is supposed to be the reflexive url to the page, sans any GET data
				self::$pageURI     = $_SERVER['REQUEST_URI'];
				self::$pageScript  = $_SERVER['SCRIPT_NAME'];
				self::$pageRequest = implode( '/', $request );
				self::$pageRoot    = self::$pageRequest;
				self::$pageData    = $path;
			}else{
				$sName = $_SERVER['SCRIPT_NAME'];
				
				self::$pageURI     = $_SERVER['REQUEST_URI'];
				self::$pageScript  = $sName;
				self::$pageRequest = $sName;
				self::$pageRoot    = substr( $sName, 0, strrpos($_SERVER['SCRIPT_NAME'],'/') );
				self::$pageData    = isset($_SERVER['PATH_INFO']) ? explode( '/', substr($_SERVER['PATH_INFO'], 1) ) : array();
			}
			
			// figure out internal roots
			$cwd = getcwd();
			
			// calculate the different roots for the site
			self::$projectRoot = substr( $cwd, 0, strripos($cwd, DIRECTORY_SEPARATOR.'www')+1 );
			self::$webRoot = self::$projectRoot.'/www';
			self::$srcRoot = self::$projectRoot.'/src';
			
			$dirs = explode( PATH_SEPARATOR, get_include_path() );
			array_unshift( $dirs, self::$srcRoot ); // standard source file

			$dir = self::$projectRoot.'/local';
			if ( file_exists($dir) ){
				array_unshift( $dirs, $dir ); // computer override
			}

			// scan the include path
			for( $i = 0, $c = count($dirs); $i < $c; $i++ ){
				$dir = $dirs[$i];
			
				if ( $dir{0} == '.' ){
					// crap, it's relative, make it absolute
					$dir = $cwd.DIRECTORY_SEPARATOR.$dir;
				}
			
				self::$phpLibraries[] = $dir;
				
				$dir = rtrim($dir,'/');
			
				if ( substr($dir, -3) === 'php' && file_exists($dir.'/../doc') ){
					self::$fileDocuments[] = $dir.'/../doc';
				}
			}
		}
	}
}
