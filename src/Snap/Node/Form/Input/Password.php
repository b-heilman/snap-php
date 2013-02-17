<?php

namespace Snap\Node\Form\Input;

class Password extends \Snap\Node\Form\Input\Basic {
	protected function parseSettings( $settings = array() ){
		$settings['type'] = 'password';
		
		parent::parseSettings( $settings );
	}
}
