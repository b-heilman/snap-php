<?php

namespace Snap\Prototype\Installation\Node\Install;

class Row extends \Snap\Node\Form\Virtual {
	
	protected function parseSettings( $settings = array() ){
		if ( isset($settings['prototype']) && $settings['prototype'] instanceof \Snap\Prototype\Installation\Lib\Prototype ){
			$prototype = $settings['prototype'];	
		}else{
			throw new \Exception('An installation row needs to feed of an instance of installation_prototype_proto');
		}
		
		$settings['model'] = new \Snap\Prototype\Installation\Model\Form\Row( $prototype );
		$settings['view'] = '\Snap\Prototype\Installation\Node\View\RowForm';
		
		parent:: parseSettings($settings);
	} 
}