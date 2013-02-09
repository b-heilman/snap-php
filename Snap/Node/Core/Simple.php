<?php
// inline HTML objects, such as scripts or inputs
namespace Snap\Node\Core;

class Simple extends \Snap\Lib\Core\StdObject
	implements \Snap\Node\Core\Snapable {
	// TODO : the id shit needs to be cleaned up
	protected static
		$logs = array();
	
    protected
		$id, 
		$class, 
		$tag, 
		$page = null,
		$parent = null, 
		$clones = null, 
		$flags = array(),
		$dead = false,
    	$extender = null;
    
    static protected 
		$index = array();

	// set up the tag, class, and id of the object.  Initialize the attributes of the object to nothing and give it an unique id.
	public function __construct( $settings = array() ){
		parent::__construct();
		
		$this->parseSettings($settings);	
	}
	
	protected function parseSettings( $settings ) {
		$this->id = isset($settings['id']) ? $settings['id'] : false;
		$this->tag = isset($settings['tag']) ? $settings['tag'] : 'span';
		$this->class = ( isset($settings['class']) ? $settings['class'].' ' : '' ) . $this->baseClass();
	}
	
	protected function baseClass(){
		return '';
	}
	
	// set a flag for this element
	public function setFlag( $flag ){
		$this->flags[$flag] = true;
	}
	// read a flag for this element
	public function getFlag( $flag ){
		return isset($this->flags[$flag]);
	}
	
	// copy the class from another object
	public function copyClass( Simple $from ){
		//TODO what if the class is already there?
		$this->class = $from->class;
	}

	public function addClass( $class ){
        $this->class .= ' '.$class;
    }

    public function removeClass( $class ){
        $this->class = str_ireplace( $class, '', $this->class );
    }

    public function clearClass(){
        $this->class = '';
    }

    public function removeFromParent(){
    	if ( !is_null($this->parent) ){
    		if ( is_array($this->parent) ){
    			foreach( $this->parent as $parent ){
    				$parent->remove( $this );
    			}
    		}else{
    			$this->parent->remove( $this );
    		}
    	}
    }

    public function closest( $class ){
    	$parent = $this->parent;
    	
    	while( $parent != null && !($parent instanceof $class) ){
    		$parent = $parent->parent;
    	}
    	
    	return $parent;
    }
    
	protected function getAttributes(){
    	return ( $this->id ? "id=\"{$this->id}\"" : '' )
    		. ( $this->class != '' ? " class=\"{$this->class}\"" : '' );
    }
    
	// Copy the important information of the object
	public function copy( $in ){
		$this->atts = ($in->atts);
		$this->tag = ($in->tag);
		if ( $in->id != '' ){
			$this->id = $in->id.'_'.count($this->clones); 	// You can't have two objects named the same ids,
															//thus copying it literally would break
		}
	}
	
	public static function getSettings(){
		return array(
			'id'    => 'id of the element',
			'class' => 'css class(es) of the element',
			'tag'   => 'the tag of the element'
		);
	}
	
	// when you want to print_r something
	public function log( $obj ){
		$t = new \stdClass();
		$t->class = get_class($this);
		$t->msg = $obj;
	
		static::$logs[] = $t;
	}
	
	// remove this element from the DOM
	public function kill(){
		$this->dead = true;
	}
	
	public function clear(){ return; }

	// Is the object dynamic, default for all objects will be false since there is no dynamic data being read in.
	// Generate the entire element.  Note there is no inner HTML to worry about.
	public function html(){
		return $this->dead ? '' : "<{$this->tag} {$this->getAttributes()} />";
	}
	
    public function inner(){ return ''; }
    
	
	// Produce the entire object from string.  In this instance it simply wraps the html() function
	public function toString(){
		// this tag and any other elements that create the full element
		// TODO : really need to get rid of this
		return $this->html();
	}
	
	public function hasId(){
		return ( $this->id !== false );
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function setPage( \Snap\Node\Core\Page $page ){
		$this->page = $page;
	}
	
	// add this element to the DOM
	public function setParent( \Snap\Node\Core\Snapping $parent ){
		$this->parent = $parent; // One parent... screw it
	}

	public function getParent(){
		return $this->parent;
	}
	
	public function hasParent(){
		return !is_null($this->parent);
	}
	
	public function setExtender( \Snap\Lib\Node\Extender $extender ){
		$this->extender = $extender;
	}
	
	public function hasExtender(){
		return $this->extender != null;
	}
	
	// clone the object
	public function makeClone(){
		return $this;
	}
	
	// Just to make it so people can be easy
	public function __toString(){
		return $this->html();
	}

	public function __unset( $junk='' ){
		if ( $this->id != '' ){
			unset( self::$index[$this->id] );
		}
	}
}
