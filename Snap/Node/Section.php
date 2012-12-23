<?php

namespace Snap\Node;

class Section extends \Snap\Node\Linear { // TODO need a better name

	public function __construct( $settings = array() ){
		if ( isset($settings['class']) ){
			$settings['class'] .= ' dl-section';
		}else{
			$settings['class'] = ' dl-section';
		}
		
		parent::__construct( $settings );
	}

	public function setOrder($order){
		$j = 0;
		
		if ( !$this->orderSet ){
			$this->inside->walk(function ($el) use (&$j, &$order){
				if ( $el instanceof \Snap\Node\Linear ){
		    		$t = $el->setOrder($order);
		            $order += $t;
		            $j += $t;
		    	}
		    });
		    
		    $this->orderSet = true;
		}

	    return $this->orderSet;
    }

	public function inner(){
		if ( !$this->orderSet ){
			$this->setOrder( 0 );
		}

	    return parent::inner();
    }
}