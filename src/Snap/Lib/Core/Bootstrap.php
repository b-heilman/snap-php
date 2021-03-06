<?php

namespace Snap\Lib\Core;

require_once( 'Snap/Lib/Core/StdObject.php' );

class Bootstrap extends StdObject {

	static private 
		$init = false,
		$libraries,
		$prototypeRoots = array(); // for later use when I am including js + css files

	/**
	 * Parse out the root directories
	 *------
	 * expected layout of a project directory is project/www and project/php
	 *------
	 * Need the PHP roots to find the prototypes
	 * 
	 */
	static public function init(){
		parent::init();
		
		if ( !self::$init ){
			$changes = false;
			self::$init = true;
			
			if ( defined('EXTRA_INCLUDES') ){
				$extra = EXTRA_INCLUDES;
				foreach( $extra as $key => $include ){
					if ( $include{0} == '.' ){
						$extra[$key] = static::$projectRoot.'/'.$include;
					}
				}
			}else{
				$extra = array();
			}

			set_include_path( implode(PATH_SEPARATOR, array_merge(static::$phpLibraries,$extra)) );
		}
	}

	static public function getAvailablePrototypes(){
		$prototypes = array();

		$dirs = static::$phpLibraries;
			
		// scan the include path
		for( $i = 0, $c = count($dirs); $i < $c; $i++ ){
			$dir = $dirs[$i];
				
			$subdirs = scandir( $dir );
			foreach( $subdirs as $instance ){
				if ( $instance{0} != '.' ){
					$loc = $dir.'/'.$instance.'/Install';
					if ( file_exists($loc) ){
						$prototypes[] = '\\'.$instance;
					}
					
					$loc = $dir.'/'.$instance.'/Prototype';
					if ( file_exists($loc) ){
						$prototypeRoots[ $loc ] = $instance.'\\'.'Prototype';
					}
				}
			}
		}
		
		foreach( $prototypeRoots as $loc => $name ){
			$dirs = scandir( $loc );
			
			foreach( $dirs as $prototype ){
				if ( $prototype{0} != '.' ){
					$prototypes[] = '\\'.$name.'\\'.$prototype;
				}
			}
		}

		$prototypes = array_unique( $prototypes );

		return $prototypes;
	}

	static public function testFile( $file ){
		return stream_resolve_include_path( $file );
	}
	
	// include a file in the php path
	static public function includeFile( $file ){
		return include( $file );
	}
	
	// load a file in the php path, return back the content
	static public function loadFile( $file ){
		ob_start();
		
		if ( static::includeFile( $file ) ){
			$rtn = ob_get_contents();
			ob_end_clean();
		
			return $rtn;
		}else{
			ob_end_clean();
			
			return null;
		}
	}
	
	/**
	 * Builds a path for the file types
	 *----
	 * 
	 */
	static public function getExactFile( $type, $file ){
		return "Snap/$type/$file";
	}
	
	static public function getRelatedFile( $obj, $search, $file ){
		if ( is_object($obj) ){
			$class = get_class( $obj );
		}else{
			$class = $obj;
		}
		
		if ( is_array($search) ){
			for( $pos = false, $i = 0, $c = count($search); $pos === false && $i < $c; $i++ ){
				$pos = strpos( $class , $search[$i] );
			}
		}else{
			$pos = strpos( $class , $search );
		}
		
		if ( $pos === false ){
			return null;
		}else{
			return str_replace('\\', '/', substr($class,0,$pos).$file );
		}
	}
	
	static public function getExtensionFile( $obj, $search, $type, $ext ){
		if ( is_object($obj) ){
			$class = get_class( $obj );
		}else{
			$class = $obj;
		}
		
		return str_replace('\\', '/', str_replace($search,$type,$class).$ext );
	}
	
	static protected function getFile( $obj, $file, $search, $type, $ext ){
		return ( $obj )
			? ( $file != null
					? static::getRelatedFile( $obj, $search, $type.'/'.$file )
					: static::getExtensionFile( $obj, $search, $type, $ext )
			) : static::getExactFile( $type, $file );
	}
	
	static public function getTemplateFile( $obj, $file = null ){
		return static::getFile( $obj, $file, 'Node', 'Template', '.php');
	}
	
	static public function getLibraries(){
		return self::$fileLibraries;
	}
	
	static public function includeClass( $className ){
		$className = str_replace('\\', '/', $className);
		
		if ( stream_resolve_include_path( $className.'.php' ) ){
			$include = null;
			
			$lead = explode( '/', $className );
			$lead = $lead[1];
			
			// TODO : this should be offloaded to the classes themselves
			if ( $lead == 'Adapter' ){
				$include = static::getExtensionFile( $className, 'Adapter', 'Config', '.php' );
			}elseif ( $lead == 'Prototype' ){
				$include = static::getRelatedFile( $className, array('Node','Lib','Install','Model'), 'Install/config.php' );
			}
			
			if ( $include ){
				static::includeConfig( $include );
			}
			
			include_once( $className.'.php' );
		}
	}
	
	static public function includeConfig( $file ){
		$pos = strrpos( $file, '/' );
		$myFile = substr($file, 0, $pos).'/my_'.substr($file, $pos+1);
		
		$found = stream_resolve_include_path( $myFile );
		
		if ( $found !== false ) {
			include_once $found;
		}
		
		$found = stream_resolve_include_path( $file );
		if ( $found !== false ) {
			include_once $found;
			return true;
		}else{
			return false;
		}
	}
}

Bootstrap::init();

spl_autoload_register('\Snap\Lib\Core\Bootstrap::includeClass');

Bootstrap::includeConfig('Snap/Config/general.php');
