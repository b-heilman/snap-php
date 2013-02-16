<?php

namespace Snap\Lib\Form;

abstract class Error {
	
	protected
		$reported = false;
	
	abstract public function getError();
	
	public function markReported(){
		$this->reported = true;
	}
	
	public function isReported(){
		return $this->reported;
	}
}