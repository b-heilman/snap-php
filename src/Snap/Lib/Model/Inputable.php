<?php

namespace Snap\Lib\Model;

interface Inputable {
	public function getDisplay();
	public function getValue();
	
	static public function findAllWithValues( $values );
}