<?php

namespace Snap\Node\Form\Input;

class Password extends \Snap\Node\Form\Input\Basic {
	public function __construct( $settings = array() ){
		$settings['type'] = 'password';
		
		parent::__construct( $settings );
	}
}
