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
		
		if ( is_null($data) ){
			$this->class = str_replace( '/','\\', $class );
			$this->data = json_decode( $_GET['__reflectiveInit'], true );
		}else{
			$this->class = $class;
			$this->data = $data;
		}
	}
	
	public function isRawContent(){
		return false;
	}
	
	public function isValid(){
		return class_exists( $this->class ) && array_search( 'Snap\Node\Accessor\Reflective', class_implements($this->class) );
	}
	
	public function getContent( \Snap\Node\Core\Page $page ){
		$class = $this->class;
		
		$page->append( $el = new $class($this->data) );
		
		$page->inner();
	
		return $el->inner();
	}
	
	public function getLink( $serviceRoot, $webRoot ){
		return $serviceRoot.str_replace( '\\','/', $this->class ).'?__reflectiveInit='.urlencode( json_encode($this->data) );
	}
	
	public function getContentType(){
		return 'html';
	}
}