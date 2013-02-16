<?php

namespace Snap\Lib\Linking\Resource;

class Local {
	
	protected 
		$result = false,
		$resource,
		$file,
		$type = null,
		$ext = null;
	
	public function __construct( $resource, $file = null ){
		if ( $resource instanceof \Snap\Lib\Linking\Resource\Local ){
			$this->resource = $resource->resource;
			$this->file = $resource->file;
		}elseif ( $file == null ){
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
	
	public function getResource(){
		if ( $this->result === false ){
			if ( !$this->type ){
				throw new \Exception('Need to set a type');
			}	
		
			if ( $this->resource && $this->file ){
				$class = get_class($this->resource);
				do {
					$file = \Snap\Lib\Core\Bootstrap::getRelatedFile( $class, 'Node', $this->type.'\\'.$this->file );
					$path = \Snap\Lib\Core\Bootstrap::testFile( $file );
					$class = get_parent_class( $class );
				}while( $class && !$path );
					
				$this->result = $path ? $file : null;
			}elseif( $this->resource ){
				$class = get_class($this->resource);
				do {
					$file = \Snap\Lib\Core\Bootstrap::getExtensionFile( $class, 'Node', $this->type, $this->ext );
					
					$path = \Snap\Lib\Core\Bootstrap::testFile( $file );
					$class = get_parent_class( $class );
					
				}while( $class && !$path );
				
				$this->result = $path ? $file : null;
			}else{
				$this->result = \Snap\Lib\Core\Bootstrap::getExactFile( $this->type, $this->file );
			}
		}
		
		return $this->result;
	}
	
	public function __toString(){
		$file = $this->getResource();
		return $file ? $file : '';
	}
}