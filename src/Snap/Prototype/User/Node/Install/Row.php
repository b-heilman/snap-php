<?php

namespace Snap\Prototype\User\Node\Install;

class Row extends \Snap\Node\Form\Virtual {

	public function __construct( $settings = array() ){
		if ( !is_array($settings) ){
			$settings = array( 'prototype' => $settings );
		}

		if ( isset($settings['prototype']) && $settings['prototype'] instanceof \Snap\Prototype\Installation\Lib\Prototype ){
			$prototype = $settings['prototype'];
		}else{
			throw new \Exception('An installation row needs to feed of a prototype');
		}

		$settings['model'] = new \Snap\Prototype\User\Model\Form\Row( $prototype );

		parent::__construct( $settings );
	}
}