<?php

namespace Snap\Node\Consuming;

class ShowOn extends \Snap\Node\Consuming {

	protected 
		$show = false;
	
	public function __construct( $settings = array() ){
		parent::__construct($settings);
		$this->inside->stopExtending();
	} 
	
	protected function _consume( $data = array() ){
		if ( is_array($this->stream) ){
			$found = false;
			
			foreach( $this->stream as $stream ){
				if ( isset($data[$stream]) ){
					$found = true;
				}
			}
			
			if ( $found ){
				$this->show = true;
			}
		}else{
			if ( isset($data[$this->stream]) ){
				$this->show = true;
			}
		}
	}
	
	protected function _process(){
		if ( !$this->show ){
			$this->clear();
			$this->kill();
		}else{
			$this->inside->startExtending();
		}
	}
}