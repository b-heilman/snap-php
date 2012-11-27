<?php

namespace Snap\Node;

class Renderer extends \Snap\Node\Block {

	protected 
		$orderSet = false;

	public function inner(){
		if ( !$this->orderSet ){
			$j = 0;
			
		    $this->inside->walk(function ($el) use ($j){
		    	if ( $el instanceof \Snap\Node\Linear ){
		    		$j += $el->setOrder($j); //What!?!?  Why?  Oh... you'll understand
		    	}
		    });
		 	
		    $this->orderSet = true;
		}

	    return parent::inner();
    }
}