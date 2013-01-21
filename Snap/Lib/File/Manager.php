<?php

namespace Snap\Lib\File;

class Manager extends \Snap\Lib\Core\StdObject {
	
	protected 
		$mode = null,
		$accessor = null;
	
	static protected
		$accessors = array(
			'__reflect'  => 'Snap\Lib\File\Accessor\Reflective',
			'__document' => 'Snap\Lib\File\Accessor\Document',
			'__resource' => 'Snap\Lib\File\Accessor\Resource',
			'__www'      => 'Snap\Lib\File\Accessor\Web'
		),
		$lookUp = null;
	
	public function __construct( $accessor = null, $info = null ){
		parent::__construct();
		
		if ( self::$lookUp == null ){
			self::$lookUp = array_flip( self::$accessors );
		}
		
		if ( $accessor ){
			if ( is_object($accessor) ){
				$this->setAccessor( $accessor );
			}elseif( isset(self::$accessors[$accessor]) ){
				$class = '\\'.self::$accessors[$accessor];
			
				if ( class_exists( $class ) ){
					// calling an accessor this way, it autoloads itself
					$this->setAccessor( new $class($info) );
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
	
	public function getContent( \Snap\Node\Page $page ){
		if ( $this->accessor->isValid() ){
			return $this->accessor->getContent( $page );
		}
		
		return null;
	}
	
	// generate a link to a file in the current directory
	public function makeLink( \Snap\Lib\File\Accessor $accessor = null ){
		if ( $accessor ){
			$this->setAccessor( $accessor );
		}
		
		if ( $this->accessor->isValid() ){
			return $this->accessor->getLink( static::$pageUrl.'/'.$this->mode.'/' );
		}else return null;
	}
}