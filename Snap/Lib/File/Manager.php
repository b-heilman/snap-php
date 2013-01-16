<?php

namespace Snap\Lib\File;

class Manager extends \Snap\Lib\Core\StdObject {
	
	protected 
		$mode = null,
		$accessor = null;
	
	static protected
		$accessors = array(
			'ajax'     => 'Snap\Lib\File\Accessor\Ajax',
			'library'  => 'Snap\Lib\File\Accessor\Library',
			'resource' => 'Snap\Lib\File\Accessor\Resource',
			'www'      => 'Snap\Lib\File\Accessor\Web'
		),
		$lookUp = null;
	
	public function __construct( $autoload = false ){
		parent::__construct();
		
		if ( self::$lookUp == null ){
			self::$lookUp = array_flip( self::$accessors );
		}
		
		if ( $autoload ){
			if ( is_object($autoload) ){
				$this->setAccessor( $autoload );
			}elseif( isset($_GET['__service']) ){
				$mode = $_GET['__service'];
				$class = '\\'.self::$accessors[$mode];
			
				if ( class_exists( $class ) ){
					// calling an accessor this way, it autoloads itself
					$this->setAccessor( new $class() );
				}
			}
		}
	}
	
	public function getAccessor(){
		return $this->accessor;
	}
	
	public function setAccessor( Accessor $accessor ){
		$this->accessor = $accessor;
		$this->mode = self::$lookUp[ get_class($accessor) ];
	}
	
	public function getMode(){
		return $this->mode;
	}
	
	public function getChildManager( $directory ){
		if ( $this->accessor->fileExists($directory) ){
			$class = get_class( $this );
			return new $class( $this->accessor->clone($directory) );
		}
		
		return null;
	}
	
	public function scanDirectory(){
		return $this->accessor->scanDirectory();
	}
	
	public function changeDirectory( $directory ){
		if ( $this->accessor->fileExists($directory) ){
			$this->accessor->changeDirectory( $directory );
			return true;
		}
		
		return false;
	}
	
	public function getContent( $file = null ){
		if ( $file === null || $this->accessor->fileExists($file) ){
			return $this->accessor->getContent( $file );
		}
		
		return null;
	}
	
	// generate a link to a file in the current directory
	public function makeLink(){
		if ( $this->accessor->isValid() ){
			return $this->accessor->getLink( static::$pageUrl.'?__service='.$this->mode.'&' );
		}else return null;
	}
}