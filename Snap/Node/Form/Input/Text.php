<?php

namespace Snap\Node\Form\Input;

class Text extends \Snap\Node\Form\Input\Basic {

	protected 
		$size = null;
	
	public function __construct($settings = array() ){
		parent::__construct($settings);
	} 
	
	public function getType(){
		return $this->type;
	}
	
	protected function parseSettings( $settings = array() ){
		$settings['type'] = 'text';
		
		$this->size = isset($settings['size']) ? $settings['size'] : null;
			
		parent::parseSettings( $settings );
	}
	
	public static function getSettings(){
		parent::getSettings() + array(
			'size' => 'limit the size of the input'
		);
	}
	
	protected function getAttributes(){
		return parent::getAttributes() 
			. ($this->size != null ? " size=\"{$this->size}\"": '');
	}
}
