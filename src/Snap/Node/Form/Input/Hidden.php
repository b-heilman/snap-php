<?php

namespace Snap\Node\Form\Input;

class Hidden extends \Snap\Node\Form\Input\Basic {

	public function __construct( $settings = array() ){
		if ( !is_array($settings) ){
			$settings = array( 'input' => $settings );
		}
		
		parent::__construct( $settings );
	}
	
	protected function parseSettings( $settings = array() ){
		$settings['type']  = 'hidden';
			
		parent::parseSettings($settings);
	}
}