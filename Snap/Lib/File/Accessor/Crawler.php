<?php

namespace Snap\Lib\File\Accessor;

abstract class Crawler extends \Snap\Lib\Core\StdObject
	implements \Snap\Lib\File\Accessor {

	protected
		$code = null,
		$path = null,
		$roots = null,
		$directory = null;

	public function __construct( $path = null ){
		parent::__construct();
		
		$this->path = null;
		$this->directory = null;
		
		foreach( $this->roots as $root ){
			if ( file_exists($root.'/'.$path) ){
				$this->path = $path;
				$this->directory = $root.'/'.$path;
			}
		}
	}

	// return an array of all viable directories
	public function scanDir(){
		$temps = array();

		if ( $this->directory ){
			$dirs = scandir( $this->directory );
			foreach( $dirs as $file ) if ( $file{0} != '.' ){
				$temps[] = $file;
			}
		}

		return $temps;
	}

	// change the current directory to a child directory
	public function changeDir( $directory ){
		if ( file_exists($this->directory.'/'.$directory) ){
			$this->directory = $this->directory.'/'.$directory;
			$this->path = $this->path.'/'.$directory;
		}
	}

	// test if file or directory exists inside the current directory
	public function fileExists( $file = null ){
		if ( $file ){
			return file_exists($this->directory.'/'.$file);
		}else return $this->directory != null;
	}

	// get the full accessor path if it exists, null otherwise
	public function getPath( $file = null ){
		if ( $file ){
			if ( file_exists($this->directory.'/'.$file) ){
				return $this->path.'/'.$file;
			}else return null;
		}else return $this->path;
	}

	// get the full accessor path from the root if it exists, null otherwise
	public function getFullPath( $file = null ){
		if ( $file ){
			if ( file_exists($this->directory.'/'.$file) ){
				return $this->directory.'/'.$file;
			}else return null;
		}else return $this->directory;
	}
	
	// get an instance of the same class
	public function getClone( $file = null ){
		$class = get_class( $this );
		
		if ( $file ){
			if ( $this->fileExists($file) ){
				return $class( $this->path.'/'.$file );
			}else return null;
		}else{
			return $class( $this->path );
		}
	}

	public function isValid(){
		return $this->fileExists();
	}
	
	public function getContent(){
		if ( $this->fileExists() ){
			return file_get_contents( $this->directory );
		}else return '';
	}
}