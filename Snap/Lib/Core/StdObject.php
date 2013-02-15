<?php

namespace Snap\Lib\Core;

class StdObject {
	
	static protected 
		$projectRoot,
		$pageRequest,
		$pageScript = null,
		$pageData,
		$phpRoot,
		$webRoot,
		$fileDocuments = array(),
		$phpLibraries = array();
	
	public function __construct(){
		if ( self::$pageScript == null ){
			self::loadAll();
		}
	}
	
	static protected function loadAll(){
		self::loadVariables();
	}
	
	static protected function loadVariables(){
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
		self::$pageRequest = implode( '/', $request );
		self::$pageScript = $_SERVER['SCRIPT_NAME'];
		self::$pageData = $path;
		
		// figure out internal roots
		$cwd = getcwd();
		
		// calculate the different roots for the site
		self::$projectRoot = substr( $cwd, 0, strripos($cwd, DIRECTORY_SEPARATOR.'www')+1 );
		self::$webRoot = self::$projectRoot.'/www';
		self::$phpRoot = self::$projectRoot.'/php';
		
		$dirs = explode( PATH_SEPARATOR, get_include_path() );
		array_unshift( $dirs, self::$phpRoot );
			
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