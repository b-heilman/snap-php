<?php

namespace Snap\Lib\Core;

class Configuration extends \stdClass {
	
	public
		$__path = null,
		$__scope = null;
	
	public function __construct( $reference, $path ){
		if ( $path{0} != '/' ){
			$class = get_class( $reference );
			
			if ( ($pos = strpos('Node',$class)) !== false 
				|| ($pos = strpos('Model',$class)) !== false 
				|| ($pos = strpos('Control',$class)) !== false 
				|| ($pos = strpos('Template',$class)) !== false 
				|| ($pos = strpos('Adapter',$class)) !== false ){
				$this->__scope = substr( str_replace('\\', '/', $class), 0, $pos ).'Config/'.$path;
			}
		}elseif( $path{1} == '/' ){
			$this->__scope = 'Snap/Config'.substr( $path, 1 );
		}else{
			$this->__scope = $path;
		}
		
		if ( $this->__scope ){
			$this->__path = $this->__scope.'.php';
			
			if ( $p = stream_resolve_include_path($this->__path) ){
				include_once( $p );
			}
		}
	}
}