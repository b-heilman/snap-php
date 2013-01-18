<?php

namespace Snap\Lib\Core;

class StdObject {
	static protected 
		$pageUrl = null,
		$projectRoot,
		$phpRoot,
		$webRoot,
		$fileDocuments = array(),
		$phpLibraries = array();
	
	public function __construct(){
		if ( static::$pageUrl == null ){
			static::loadAll();
		}
	}
	
	static protected function loadAll(){
		static::loadVariables();
	}
	
	static protected function loadVariables(){
		if ( isset($_SERVER['REDIRECT_URL']) ){
			// REDIRECT_URL - PHP_SELF
			// http://localhost/test/ym/something/or/other?woot=1
			// '/test/ym/something/or/other' - '/redirect.php'
			// /ym/something/or/other
		
			$find = explode( '/', $_SERVER['PHP_SELF']  );
			$path = explode( '/', $_SERVER['REDIRECT_URL'], count($find) + 1 );
		
			$basePath = array_pop( $path );
		}elseif( isset($_SERVER['PATH_INFO']) ){
			// PATH_INFO
			// http://localhost/test/index.php/ym/something/or/other?woot=1
			// /ym/something/or/other
			$basePath = $_SERVER['PATH_INFO'];
		}else{
			$basePath = $_SERVER['PHP_SELF'];
		}
		
		// this is supposed to be the reflexive url to the page, sans any GET data
		self::$pageUrl = $basePath;
		
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