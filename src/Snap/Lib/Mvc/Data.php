<?php

namespace Snap\Lib\Mvc;

interface Data {
	
	// variable functions
	public function bind( $var, &$value );
	public function setVar( $var, $value = null );
	public function hasVar( $var );
	public function getVar( $var );
	
	// collection functions
	public function has( $pos );
	public function count();
	public function add( $data );
	public function get( $pos );
	
	// fancy collection functions
	public function getPrimary();
	public function merge( \Snap\Lib\Mvc\Data $in );
	public function makeUnique( $hashValueFunction );
}