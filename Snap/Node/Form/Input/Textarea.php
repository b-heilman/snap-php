<?php

namespace Snap\Node\Form\Input;

class Textarea extends \Snap\Node\Form\Input {

	protected 
		$rows, 
		$cols;
	
	public function __construct( $settings = array() ){
		$settings['tag'] = 'textarea';
		
		parent::__construct( $settings );

		$this->rows = isset($settings['rows']) ? $settings['rows'] : null;
		$this->cols = isset($settings['cols']) ? $settings['cols'] : null;
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'cols' => 'number of columns for the textarea',
			'rows' => 'number of rows for the textarea'
		);
	}
	
	protected function getAttributes(){
		return parent::getAttributes() 
			. ( $this->cols != null ? "cols=\"$this->cols\"" : '' )
			. ( $this->rows != null ? "rows=\"$this->rows\"" : '' );
	}
	
	public function getType(){
		return 'textarea';
	}
	
	public function inner(){
		parent::inner();
		
		return htmlentities( $this->input->getValue() );
	}
}