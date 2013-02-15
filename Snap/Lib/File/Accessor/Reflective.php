<?php

namespace Snap\Lib\File\Accessor;

class Reflective 
	implements \Snap\Lib\File\Accessor {
	
	protected
		$class,
		$data;
	
	public function __construct( $node, $data = null ){
		if ( $node instanceof \Snap\Node\Core\Snapable ){
			$class = get_class( $node );
		}else{
			$class = $node;
		}
		
		if ( $data == null ){
			$this->class = str_replace( '/','\\', $class );
			$this->data = json_decode( $_GET['__reflectiveInit'], true );
		}else{
			$this->class = $class;
			$this->data = $data;
		}
	}
	
	public function isValid(){
		return class_exists( $this->class ) && array_search( 'Snap\Node\Accessor\Reflective', class_implements($this->class) );
	}
	
	public function getContent( \Snap\Node\Core\Page $page ){
		$class = $this->class;
		
		$page->append( new $class($this->data) );
		
		return $page->inner();
	}
	
	public function getLink( $root ){
		return $root.str_replace( '\\','/', $this->class ).'?__reflectiveInit='.urlencode( json_encode($this->data) );
	}
	
	public function getContentType(){
		return 'html';
	}
}