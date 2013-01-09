<?php
// This of the class for box and collection objects.

namespace Snap\Node;

use 
	\Snap\Node\Snapable,
	\Snap\Node\Text;

class Block extends \Snap\Node\Simple 
	implements \Snap\Node\Snapping, \Snap\Node\Processable, \Snap\Node\Finalizable {
		
	private static 
		$factory = null;
	
	protected
		$inside, 
		$stream = null, 
		$rendered;
	
	// Create the arrays that contain the children for the element
	// TODO : parse all the other 
	static public function setFactory( \Snap\Lib\Node\StackFactory $factory ){
		self::$factory = $factory;
	}
	
	public function __construct( $settings = array() ){
		if ( self::$factory == null ){
			self::setFactory( new \Snap\Lib\Node\StackFactory() );
		}
		
		$this->inside = self::$factory->makeStack( $this );
		$this->rendered = '';
		
		$this->parseSettings($settings);
		
		/*
		 * Moving this to an extension because I want the page to be defined before the template rendering kicks off.
		 * This simply means template will need to be appended before they are processed, and it shouldn't break anything
		 */
		// $this->build();
	}
	
	protected function parseSettings( $settings = array() ){
		if ( !isset($settings['tag']) ){
			$settings['tag'] = 'div';
		}
		
		parent::parseSettings( $settings );

		$this->stream = isset($settings['stream']) ? $settings['stream'] : null; 
	}
	
	public function build(){}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'stream' => "The stream this element's process data is pushed to"
		);
	}
	
	public function getStack(){
		return $this->inside;
	}
	
	// Copy the child elements
	public function copy( $in ){
		parent::copy( $in );
		$this->inside = $in->inside;
		$this->rendered = $in->rendered;
	}
	
	// clear all the child elements
	public function clear(){
		$this->inside->clear();
		$this->rendered = '';
	}
	// the count of child nodes of this object
	public function childCount(){
	    return $this->inside->count();
	}
	//
	public function get($place){
		return $this->inside->get($place);
	}
	
	// This element is taking control of another element
	public function verifyControl( Snapable $in ){
		$parent = $in->getParent();
		
		if ( $parent == null || $parent != $this ){
			$in->setParent( $this );
			$this->takeControl($in);
		}
	}
	
	// Register all the way up the tree a node has been added
	// TODO : this name is retarded
	protected function takeControl( Snapable $in ){
		if ( $this->parent ){
			error_log( get_class($this->parent) );
			$this->parent->takeControl( $in );
			
			// TODO : this can not be performance friendly...
			if ( $in instanceof Block ){
				$c = $in->inside->count();
				for( $i = 0; $i < $c; $i++ ){
					$t = $in->inside->get($i);
					
					if ( $in instanceof Snapable ){
						$this->takeControl( $t );
					}
				}
			}
		}
	}
	
	// Any function that appends an element 
	protected function pend( Snapable $in ){
		if ( $in->hasId() ){
			if ( $in->hasParent() ){
				$in = $in->makeClone();
			}

			$id = $in->getId();
			
			if ( isset(self::$index[$id]) ){
				if ( self::$index[$id] !== $in ){
					throw new \Exception('Can not have two objects with the same id ('.$id.') for class '
						.get_class($in).' with parent '.get_class($this) );
				}
			}else{
				self::$index[$id] = $in;
			}
		}

		$this->rendered = '';

		$this->verifyControl($in);

	    return $in;
	}

	public function remove( Snapable $ele ){
		$this->inside->remove( $ele );
	}

	public function removeAt( $where ){
		$this->inside->removeAt( $where );
	}
	
	// add the element to the chidren array.  if it's dynamic also add it to the child array.  Note we are using pointers here to save memory.
	public function append( Snapable $in, $ref = null ){
		return $this->inside->add( $this->pend($in), true, $ref );
	}
	// TODO are we using this anywhere?
	public function appendAt( Snapable $in, $where = 0, $ref = null ){
		return $this->inside->addAt( $this->pend($in), $where, $ref );
	}

	public function appendAfter( Snapable $ele, Snapable $in, $ref = null ){
		return $this->inside->addAgainst( $ele, $this->pend($in), 0, $ref );
	}

	public function appendBefore( Snapable $ele,  Snapable $in, $ref = null ){
		return $this->inside->addAgainst( $ele, $this->pend($in), -1, $ref );
	}

	public function prepend( Snapable $in, $ref = null ){
		return $this->inside->add( $this->pend($in), false, $ref );
    }

    /**
     * @return Snapable
     */
	public function getElementByReference( $ref ){
		return $this->inside->getReference( $ref );
	}
	
	// return all of the elements that are of a particular class.
	// note this is the the css class but the actual php element class
	public function getElementsByClass($class, $blockOn = null ){
		return $this->inside->getElementsByClass( $class, $blockOn );
	}
	
	// when writing to an element, and inline element is created and appended
	public function write( $str, $settings = array() ){
		if ( is_string($settings) ){
			$settings = array('class' => $settings);
		}
		
		if ( !isset($settings['tag']) ){
			$settings['tag']  = 'span';
		}
		
		$settings['text'] = $str;
		
		return $this->append( new Text( $settings ) );
	}
	
	// when you want to print_r something
	public function debug($obj, $class = ''){
	    $this->append( new Text(array(
	    	'tag'   => 'pre', 
	    	'text'  => print_r($obj, true), 
	    	"class" => "code_debug $class"
	    )) );
	}
	
	public function childDump(){
		$this->debug( $this->inside->getChildClasses() );
	}

	// TODO : these may need to get moved somewhere
	static public function getElementById( $id ){
		if ( isset(self::$index[$id]) )
        	return self::$index[$id];
       	else
        	return false;
    }

    static public function removeElementById( $id ){
    	if ( isset(self::$index[$id]) ){
        	$t = self::$index[$id];
        	return $t->removeFromParent();
    	}else
        	return false;
    }
	
	public function process(){
		$this->_process();
	}

	protected function _process(){}
	
	public function finalize(){
		$this->_finalize();
	}
    
	protected function _finalize(){}
	
	public function inner(){
		if ( $this->rendered == '' ){
			$this->inside->render();
			$inside = '';
			
			$this->inside->walk( function( $node ) use ( &$inside ) {
				if ( $node instanceof Snapable ){
					$inside .= $node->html();
				}else{
					$inside .= $node; // either it's a string or something can can be caste to one
				}
			});
			
			$this->rendered = $inside;
		}
		
		$addition = '';
		
		if ( $this instanceof \Snap\Node\Actionable\Inline ){
			$inline = $this->getInlineJavascript();
			if ( $inline && is_string($inline) ){
				$addition = "\n<script type='text/javascript'>{$inline}</script>";
			}
		}
		
		return $this->rendered.$addition;
	}
	
	protected function _html( $inner ){
		return "<{$this->tag} {$this->getAttributes()}>{$inner}</{$this->tag}>";
	}
	
	public function html(){
		if ( $this->dead ){
			return '';
		}else{
			return $this->_html( $this->inner() );
		}
	}
}
