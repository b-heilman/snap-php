<?php

namespace Snap\Lib\Linking\Resource;

class Local {
	
	public 
		$page,
		$resouce,
		$file;
	
	public function __construct( \Snap\Node\Core\Page $page, $resource, $file = null ){
		$this->page = $page;
		
		if ( $file == null ){
			if ( is_string($resource) ){
				$this->resource = null;
				$this->file = $resource;
			}else{
				$this->resource = $resource;
				$this->file = $file;
			}
		}else{
			$this->resource = $resource;
			$this->file = $file;
		}
	}
	
	public function getResource( $type, $ext ){
		if ( $this->resource && $this->file ){
			$class = get_class($this->resource);
			do {
				$file = \Snap\Lib\Core\Bootstrap::getRelatedFile( $class, 'Node', $type.'\\'.$this->file );
				
				$path = \Snap\Lib\Core\Bootstrap::testFile( $file );
				$class = get_parent_class( $class );
			}while( $class && !$path );
				
			return $path ? $file : null;
		}elseif( $this->resource ){
			$class = get_class($this->resource);
			do {
				$file = \Snap\Lib\Core\Bootstrap::getExtensionFile( $class, 'Node', $type, $ext );
				
				$path = \Snap\Lib\Core\Bootstrap::testFile( $file );
				$class = get_parent_class( $class );
				
			}while( $class && !$path );
			
			return $path ? $file : null;
		}else{
			return \Snap\Lib\Core\Bootstrap::getExactFile( $type, $this->file );
		}
	}
	
	public function getLink( $type, $ext ){
		return $this->page->makeResourceLink( $this->getResource($type,$ext) );
	}
}