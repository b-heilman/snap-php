<?php

namespace Snap\Lib\Control;

// TODO : this is a stop gap until the refactoring
class Redirect extends \Exception {
	
	protected
		$redirectTo;
	
	/**
	 * @param message[optional]
	 * @param code[optional]
	 * @param previous[optional]
	 */
	public function __construct ($message = null, $code = null, $previous = null) {
		parent::__construct( 'redirect requested', $code, $previous );
		
		$this->redirectTo = $message;
	}
	
	public function getRedirect(){
		return $this->redirectTo;
	}
}