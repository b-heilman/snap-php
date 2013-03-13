<?php

namespace Snap\Node\Form\Input;

class Select extends \Snap\Node\Form\Input\Abstracted {
	
	protected 
		$options;
	
	protected function parseSettings( $settings = array() ){
		$settings['tag'] = 'select';
		
		if ( !isset($settings['input']) || !($settings['input'] instanceof \Snap\Lib\Form\Input\Optionable) ){
			throw new \Exception( 'A '.get_class($this).' requires an instance of \Snap\Lib\Form\Input\Optionable,'
					.' instead recieved '.get_class($settings['input']) );
		}
		
		parent::parseSettings( $settings );
	}

	public function getType(){
		return 'select';
	}
	
	public function inner(){
		$render = '';
		$val = $this->input->getValue();
		
		if ( is_array($val) ){
			$options = array();
			foreach( $val as $v ){
				$options[ $v ] = true;
			}
		}else{
			$options = array( $val => true );
		}
		
		foreach( $this->input->getOptions() as $key => $dis ){
			if ( is_array($dis) ){
				$render .= "<optgroup label=\"$key\">";
				foreach ($dis as $k => $d){
					$render .= '<option value="'.htmlentities($k).'" '
						.( isset($options[$k]) ? 'selected="true"' : '' ).">$d</option>";
				}
			}else{
				$render .= '<option value="'.htmlentities($key).'" '
					.( isset($options[$key]) ? 'selected="true"' : '' ).">$dis</option>";
			}
		}
			
		$this->rendered = $render;
		
		return parent::inner();
	}
	
	public function getName(){
		if ( $this->input->allowsMultiple() ){
			return parent::getName().'[]';
		}else return parent::getName();
	}
	
	protected function getAttributes() {
		if ( $this->input->allowsMultiple() ){
			return 'multiple="multiple"'. parent::getAttributes();
		}else return parent::getAttributes();
	}
}
