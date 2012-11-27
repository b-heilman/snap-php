<?php

namespace Snap\Node\Form\Input;

class Radio extends \Snap\Node\Form\Input\Pickable {

	protected 
		$checked;
	
	public function __construct( $settings = array() ){
		$settings['type'] = 'radio';
		
		parent::__construct( $settings );
	} 
}