<?php

namespace Snap\Node\Form\Input;

class File extends Basic {
	
	public function __construct( $settings = array() ){
		$settings['type'] = 'file';
		
		parent::__construct( $settings );
	}
	
	protected function getInputValue(){
		$val = $this->input->getValue();
		
		return $val['tmp_name'];
	}
	
	protected function getAttributes(){
		return \Snap\Node\Form\Input::getAttributes() . " type=\"{$this->type}\"";
	}
}