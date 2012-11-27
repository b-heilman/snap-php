<?php

namespace Snap\Node\Form\Input;

class Checkbox extends \Snap\Node\Form\Input\Pickable {

	public function __construct( $settings = array() ){
		$settings['type'] = 'checkbox';
		
		parent::__construct( $settings ); // we're gonna need to override a few things
	}
}