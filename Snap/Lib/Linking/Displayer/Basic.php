<?php

// TODO : gotta deal with this

namespace Snap\Lib\Linking\Displayer;

class Basic extends \Snap\Node\Core\Block 
	implements \Snap\Lib\Linking\Displayer {
	
	public function setData( array $data ){
		$this->write( $data[linking_linkanator_CONTENT] );
	}
}