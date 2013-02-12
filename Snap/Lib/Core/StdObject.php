<?php

namespace Snap\Lib\Core;

class StdObject {
	
	static protected 
		$pageUrl = null,
		$pageRename,
		$pagePath,
		$projectRoot,
		$phpRoot,
		$webRoot,
		$fileDocuments = array(),
		$phpLibraries = array();
	
	public function __construct(){
		if ( self::$pageUrl == null ){
			static::loadAll();
		}
	}
	
	static protected function loadAll(){
		static::loadVariables();
	}
	
	static protected function loadVariables(){
		if ( isset($_SERVER['PATH_INFO']) ){
			// direct access to script
			
			static::$pagePath = $_SERVER['PATH_INFO'];
			static::$pageRename = null;
		}elseif( isset($_SERVER['REDIRECT_URL']) ){
			// rename access to script
			
			$url = $_SERVER['REDIRECT_URL'];
			$pos = strpos( $url, '/', 1 );
			
			static::$pagePath = substr( $url, $pos );
			static::$pageRename = substr( $url, 1, $pos-1 );
		}else{
			static::$pagePath = '';
			static::$pageRename = null;
		}
		
		// this is supposed to be the reflexive url to the page, sans any GET data
		self::$pageUrl = $_SERVER['SCRIPT_NAME'];
		
		// figure out internal roots
		$cwd = getcwd();
		
		static::$projectRoot = substr( $cwd, 0, strripos($cwd, DIRECTORY_SEPARATOR.'www')+1 );
		static::$webRoot = static::$projectRoot.'/www';
		static::$phpRoot = static::$projectRoot.'/php';
		
		$dirs = explode( PATH_SEPARATOR, get_include_path() );
		array_unshift( $dirs, static::$phpRoot );
			
		// scan the include path
		for( $i = 0, $c = count($dirs); $i < $c; $i++ ){
			$dir = $dirs[$i];
		
			if ( $dir{0} == '.' ){
				// crap, it's relative, make it absolute
				$dir = $cwd.DIRECTORY_SEPARATOR.$dir;
			}
		
			static::$phpLibraries[] = $dir;
			
			$dir = rtrim($dir,'/');
		
			if ( substr($dir, -3) === 'php' && file_exists($dir.'/../doc') ){
				static::$fileDocuments[] = $dir.'/../doc';
			}
		}
	}
}