<?php

namespace Snap\Lib\Control;

// TODO : this is a stop gap until the refactoring
class Reroute extends \Exception {
	
	protected
		$rerouteTo;
	
	/**
	 * @param message[optional]
	 * @param code[optional]
	 * @param previous[optional]
	 */
	public function __construct ($message = null, $code = null, $previous = null) {
		parent::__construct( 'redirect requested', $code, $previous );
		
		$this->rerouteTo = $message;
	}
	
	public function getReroute(){
		return $this->rerouteTo;
	}
}