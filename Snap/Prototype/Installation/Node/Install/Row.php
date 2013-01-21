<?php

namespace Snap\Prototype\Installation\Node\Install;

class Row extends \Snap\Node\Core\ProducerForm {
	
	protected 
		$prototype;
	
	protected function parseSettings( $settings = array() ){
		if ( isset($settings['prototype']) && $settings['prototype'] instanceof \Snap\Prototype\Installation\Lib\Prototype ){
			$this->prototype = $settings['prototype'];	
		}else{
			throw new \Exception('An installation row needs to feed of an instance of installation_prototype_proto');
		}
		
		parent:: parseSettings($settings);
	} 
	
	public function baseClass(){
		return 'prototype-row';
	}
	
	protected function getTemplateVariables(){
		return array(
			'prototype' => $this->prototype
		);
	}
	
	protected function processInput( \Snap\Lib\Form\Data\Result &$formData ){
		$name = $this->prototype->name;
		
		if ( $formData->hasChanged($name) ){
			if ( $formData->getValue($name) ){
				// install it
				return new \Snap\Prototype\Installation\Lib\Installer( $this->prototype );
			}else{
				// uninstall it
				return new \Snap\Prototype\Installation\Lib\Uninstaller( $this->prototype );
			}
		}
		
		return null;
	}
}