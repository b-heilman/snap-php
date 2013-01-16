<?php

namespace Snap\Lib\File\Accessor;

class Ajax 
	implements \Snap\Lib\File\Accessor {
	
	protected
		$class,
		$data;
	
	public function __construct( $class = null, $data = null ){
		$this->class = $class;
		$this->data = $data;
	}
	
	public function isValid(){
		return class_exists( $this->class );
	}
	
	public function getContent(){
		$class = $_GET[ '__ajaxClass' ];
		$vars = json_decode( $_GET['__ajaxInit'] );
		
		$node = new $class( $vars );
		
		return $node->html();
	}
	
	public function getLink( $root ){
		return $root.'__ajaxClass='.urlencode( $this->class )
			.'&__ajaxInit='.urlencode( json_encode($this->data) );
	}
}