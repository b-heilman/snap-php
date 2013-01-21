<?php

namespace Snap\Lib\Linking\Control;

// TODO : this should be removed ?
class Immediate extends \Snap\Lib\Linking\Control\Basic {
	public function setPrevData( array $data){
		if ( isset($this->prevNode) && !empty($data) ){
			$this->appendPrevious( array_shift($data) );
		}
	}
	
	public function setNextData( array $data ){
		if ( isset($this->nextNode) && !empty($data) ){
			$this->appendNext( array_shift($data) );
		}
	}
}