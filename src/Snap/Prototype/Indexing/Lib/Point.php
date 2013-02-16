<?php

namespace Snap\Prototype\Indexing\Lib;

class Point {

	private 
		$errorMode = false,
		$parent = null,
		$childPaths = array(),
		$path;

	protected 
		$children = array(),
		$settings = array(),
		$title = null,
		$stitle = null,
		$target = null,
		$error = null,
		$include = array();

	public function __construct( $path, $path_data = false ) {
		if ( is_array($path_data) ){
			$this->add( $path_data );
		}elseif ( is_string($path_data) || is_object($path_data) ){
			$this->target = $path_data;
		}

		$this->path = $path;
	}

	public function markError(){
		$this->errorMode = true;
	}

	public function add( $path_data ){
		foreach( $path_data as $key => $val ){
			if ( is_array($val) ){
				if( isset($this->children[$key]) ){
					$this->children[$key]->add( $val );
				}else{
					$c = $this->children[$key] = new Point( $key, $val );
					$c->parent = $this;

					$this->childPaths[] = $key;
				}
			}elseif ( !is_string($key) ) {
				$this->target = $val;
			}elseif ( $key{0} != '_' ) {
				$c = $this->children[$key] = new Point( $key, $val );
				$c->parent = $this;

				$this->childPaths[] = $key;
			}else{
				$this->procSetting( $key, $val );
			}
		}

		if ( isset($this->settings['title']) ){
			$this->title = $this->settings['title'];

			if ( isset($this->settings['stitle']) ){
				$this->stitle = $this->settings['stitle'];
			}else{
				$this->stitle = null;
			}
		}elseif ( isset($this->settings['stitle']) ){
			$this->title = $this->stitle = $this->settings['stitle'];
		}
	}

	// Pull out the settings that anything further down the stream might be interested in
	protected function procSetting( $key, $val ){
		if ( is_string($key) && $key{0} == '_' ){
			$k = substr($key,1);

			switch( $k ){
				case 'include' :
					$this->include = explode(';', $val);
					break;

				case 'target' :
					$this->target = $val;
					break;

				case 'error' :
					$this->error = $val;
					break;

				default :
					$this->settings[ $k ] = $val;
					break;
			}
		}
	}

	public function hasNext( $step ){
		return isset($this->children[$step]);
	}

	public function getNext( $step = false ){
		if ( $step ){
			if( isset($this->children[$step]) ){
				return $this->children[$step];
			}else{
				return false;
			}
		}else return $this->children;
	}

	public function loadInclude(){
		foreach( $this->include as $include){
			require_once $include;
		}
	}

	public function getError(){
		return $this->translateNode( (is_null($this->error)?$this->target:$this->error) );
	}

	public function getTarget(){
		return ($this->errorMode)?$this->getError():$this->translateNode( $this->target );
	}

	public function getLinkTitle(){
		return $this->stitle;
	}

	public function getTitle(){
		return $this->title;
	}

	public function getParent(){
		return $this->parent;
	}

	public function getPath(){
		return $this->path;
	}

	public function getChildPaths(){
		return $this->childPaths;
	}

	public function isLinkable(){
		return ( $this->target != null );
	}

	private function translateNode( $node ){
		if ( is_string($node) ){
			if ( preg_match('/([^\\\\\/]+)\.php$/', $node, $matches) ){
				if ( self::file_exists($node) ){
					include_once( $node );

					$class = $matches[1];

					if ( class_exists($class) ){
						$res = new $class();
					}else{
						$res = null;
					}
				}else{
					$res = false;
				}
			}elseif( class_exists($node) ){
				$res = new $node();
			}else{
				$res = false;
			}
		}elseif( is_object($node) ){
			$res = $node;
		}else{
			$res = false;
		}

		return $res;
	}

	public function getSetting( $setting = false ){
		if ( $setting )
			return ( isset($this->settings[$setting])?$this->settings[$setting]:null );
		else
			return $this->settings;
	}

	private static function file_exists ($filename){
        // Check for absolute path
        if (realpath($filename) == $filename) {
            return $filename;
        }

        // Otherwise, treat as relative path
        $paths = explode(PATH_SEPARATOR, get_include_path());
        foreach ($paths as $path) {
            if (substr($path, -1) == DIRECTORY_SEPARATOR) {
                $fullpath = $path.$filename;
            } else {
                $fullpath = $path.DIRECTORY_SEPARATOR.$filename;
            }

            if (file_exists($fullpath)) {
                return $fullpath;
            }
        }

        return false;
    }
}
