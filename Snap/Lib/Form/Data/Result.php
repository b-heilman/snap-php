<?php

namespace Snap\Lib\Form\Data;

// Data type to be bassed back by a form_node
class Result extends \Snap\Lib\Form\Data\Group {
	
	protected 
		$nodes;
	
	public function __construct( \Snap\Node\Form $form = null ){
		parent::__construct();
		
		$this->nodes = array();
		
		if ( !is_null($form) ){
	    	$eles = $form->getElementsByClass('\Snap\Node\Form\Input', '\Snap\Node\Form');
	    	$c = count( $eles );
	    	
	    	for( $i = 0; $i < $c; $i++ ){
	    		$ele = $eles[$i];
	    		
	    		$this->addNode( $ele );
	    		
	    		$input = $ele->getInput( $form );
	
	    		if ( $input !== null ){
	    			$this->addInput( $input );
	    		}
	    	}	
		}
	}

	protected function addNode( \Snap\Node\Form\Input $node ){
		$this->nodes[$node->getName()] = $node;
	}
	
	public function addInput( $in ){
		if ( $in instanceof \Snap\Lib\Form\Data\Complex ) {
	       $name = $in->getName();
    			
    		$this->data[$name] = $in;

            if ( $in->hasChanged() ){
            	$this->changes[$name] = $in;
           	}
	    } else {
    		parent::addInput( $in );
	    }
	}
	
	public function hasInput( $name ){
		return isset( $this->data[$name] );
	}
	
	/**
	 * @return \Snap\Node\Form\Input
	 */
	public function getNode( $name ){
		return isset( $this->nodes[$name] ) ? $this->nodes[$name] : null;
	}
	
	/** 
	 * @return \Snap\Lib\Form\Data\Basic
	 */
	public function getInput( $name ){
		return $this->hasInput($name) ? $this->data[$name] : null;
	}
	
	// altering it here will not trigger adding it changes list
	public function alterValue( $name, $value ){
		if ( $this->hasInput($name) ){
			$this->getInput($name)->setValue($value);
		}
	}
	
	public function getValue( $name ){
		return $this->hasInput($name) ? $this->getInput($name)->getValue() : null;
	}
	
	public function hasChanged( $name = null ){
		if ( $name == null ){
			return parent::hasChanged();
		}else{
			return isset( $this->changes[$name] );
		}
	}
	
	public function getChange( $name ){
		return ( isset($this->changes[$name]) ? $this->changes[$name] : null );
	}
}