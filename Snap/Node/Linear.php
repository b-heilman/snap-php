<?php

namespace Snap\Node;

class Linear extends \Snap\Node\Block {

	protected 
		$orderSet = false;
		
	// toggles if this row is an even or odd row
	public function setOrder($order){
		//throw new \Exception('ehh?');
		if ( !$this->orderSet ){
			$this->addClass(($order % 2)?' e ':' o ');
			$this->orderSet = true;
		}

		return 1;
	}
}