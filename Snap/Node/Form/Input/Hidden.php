<?php

namespace Snap\Node\Form\Input;

class Hidden extends \Snap\Node\Form\Input\Basic {

	public function __construct( $settings = array() ){
		$settings['type']  = 'hidden';
			
		parent::__construct($settings);
	}
}