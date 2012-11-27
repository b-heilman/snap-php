<?php

namespace Snap\Node\Form\Input;

class Select extends \Snap\Node\Form\Input\Base {
	
	protected 
		$options;
	
	public function __construct( $settings = array() ){
		$settings['tag'] = 'select';
		
		parent::__construct( $settings );

		$this->setOptions( isset($settings['options'])?$settings['options']:array() );
	}

	public function getType(){
		return 'select';
	}
	
	public function setOptions( $options ){
		$this->options = $options;
	}

	public function delOptions( $options ){
    	if ( is_array($selections) ){
    		// add this at some point
    		// TODO test this out...
    	}else{
    		$dex = array_search($options, $this->options);
    		if ( $dex !== false ){
    			array_splice($this->options, $dex, 1);
    		}
    	}
    }

	public function inner(){
		$render = '';
		$val = $this->value->getValue();
		
		foreach( $this->options as $key => $dis ){
			if ( is_array($dis) ){
				$render .= "<optgroup label=\"$key\">";
				foreach ($dis as $k => $d){
					$render .= "<option value=\"$k\""
						.( strcmp($val,$k) == 0 ? "selected=\"true\"" : '' )
						.">$d</option>";
				}
			}else{
				$render .= "<option value=\"$key\""
					.( strcmp($val,$key) == 0 ? "selected=\"true\"" : '' )
					.">$dis</option>";
			}
		}
			
		$this->rendered = $render;
		
		return parent::inner();
	}
}
