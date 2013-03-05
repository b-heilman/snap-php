<?php
// inline HTML objects, such as scripts or inputs
namespace Snap\Node\Core;

// TODO : need to fix this, shows a problem, when I do my next major refactor

class Virtual extends \Snap\Lib\Core\StdObject
	implements \Snap\Node\Core\Snapable {

	protected
		$content;
	
	public function __construct( $html = '' ){
		parent::__construct();
		
		$this->content = $html;
	}
	
	public function write( $content ){
		$this->content .= $content;
	}
	
    public function removeFromParent(){}

    public function closest( $class ){}
    
    public static function getSettings(){
		return array();
	}
	
	public function kill(){}
	
	public function clear(){}

	// Is the object dynamic, default for all objects will be false since there is no dynamic data being read in.
	// Generate the entire element.  Note there is no inner HTML to worry about.
	public function html(){
		return $this->inner();
	}
	
    public function inner(){ return $this->content; }
    
	
	// Produce the entire object from string.  In this instance it simply wraps the html() function
	public function toString(){
		// this tag and any other elements that create the full element
		// TODO : really need to get rid of this
		return $this->html();
	}
	
	public function hasId(){
		return false;
	}
	
	public function getId(){
		return null;
	}
	
	public function setPage( \Snap\Node\Core\Page $page ){
	}
	
	// add this element to the DOM
	public function setParent( \Snap\Node\Core\Snapping $parent ){
	}

	public function getParent(){
		return null;
	}
	
	public function hasParent(){
		return false;
	}
	
	public function setExtender( \Snap\Lib\Node\Extender $extender ){
	}
	
	public function hasExtender(){
	}
	
	// clone the object
	public function makeClone(){
	}
	
	// Just to make it so people can be easy
	public function __toString(){
		return $this->html();
	}
}
