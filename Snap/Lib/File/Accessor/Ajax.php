<?php

namespace Snap\Lib\File\Accessor;

class Ajax 
	implements \Snap\Lib\File\Accessor {
	
	protected
		$class,
		$data;
	
	public function __construct( $class = null, $data = null ){
		if ( $this->class == null ){
			$this->class = $_GET[ '__ajaxClass' ];
			$this->data = json_decode( $_GET['__ajaxInit'], true );
		}else{
			$this->class = $class;
			$this->data = $data;
		}
	}
	
	public function isValid(){
		return class_exists( $this->class ) && array_search( 'Snap\Node\Accessor\Ajax', class_implements($this->class) );
	}
	
	public function getContent( \Snap\Node\Page $page ){
		$class = $this->class;
		
		$page->append( new $class($this->data) );
		
		return $page->inner();
	}
	
	public function getLink( $root ){
		return $root.'__ajaxClass='.urlencode( $this->class )
			.'&__ajaxInit='.urlencode( json_encode($this->data) );
	}
	
	public function getContentType(){
		return 'html';
	}
}