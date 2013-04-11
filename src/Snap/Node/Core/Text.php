<?php

namespace Snap\Node\Core;

// HTML objects that are supposed to contain only text.  Generally h1-h5, span, p, pre tags
class Text extends Simple {
	
    protected
    	 $rendered = '';    // This is the rendered insides of the html object
	// Build the object and copy the static text to rendered.  'rendered' is inherently the inner html of the object, since these tags only
	// are meant to wrap text, this will save some time
	public function __construct( $settings = array() ){
		if ( is_string($settings) ){
			$settings = array('text' => $settings, 'tag' => 'span');
		}elseif ( !isset($settings['tag']) ){
			$settings['tag'] = 'span';
		}
		
		parent::__construct( $settings );
	}
	
	protected function parseSettings( $settings = array() ){	
		parent::parseSettings( $settings );
		
		$this->rendered = isset($settings['text']) ? $settings['text'] : '';
	}
	
	public static function getSettings(){
		$attr = parent::getSettings();
		
		$attr['text'] = 'default text for element';
		
		return $attr;
	}
	
	// writing to this object just appends the text to it's already existing text
	public function write( $str ){
		$this->rendered .= $str;
	}
	
	// Copy the important information of the object
    public function copy( $in ){
		parent::copy( $in );
		$this->rendered = $in->rendered;
	}
	
	// make the internal text blank
	public function clear(){
		$this->rendered = '';
	}
	
	// For now it's just a copy of render, but it's supposed to be all of the inner HTML
	public function inner(){
		$this->render();

    	return $this->rendered;
    }
    
    // generate the tag.  These tags require an opening and closing.  a directed call to 'rendered' is not done here just incase of later changes
    // Render the object if needed, cache the result, and then return the results.
	protected function render(){ // all children rendered, if they need rendering render them
		// since rendered is written to directly, don't need anything
	}

	public function html(){
		if ( $this->dead ){
			return '';
		}else{
			$inner = $this->inner();
			
			return "<{$this->tag} {$this->getAttributes()}>{$inner}</{$this->tag}>";
		}
	}
}
