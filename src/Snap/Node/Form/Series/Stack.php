<?php

namespace Snap\Node\Form\Series;

class Stack extends \Snap\Node\Core\Template 
	implements \Snap\Node\Actionable\Template, \Snap\Node\Core\Actionable {
	
	protected
		$series,
		$inputClass;
	
	protected function parseSettings( $settings = array() ){
		$this->series = $settings['series'];
		$this->inputClass = $settings['inputClass'];
		
		parent::parseSettings( $settings );
	}
	
	public function build(){
		parent::build();
		
		$control = $this->series->getControl();
		
		$this->append( new \Snap\Node\Form\Input\Hidden(array(
			'input' => $control,
			'class' => 'form-series-stack-count'
		)) );
	}
	
	protected function baseClass(){
		return 'form-series-stack';
	}
	
	protected function getAttributes(){
		return parent::getAttributes()." data-snap-template=\"{$this->series->getUniqueness()}\"";
	}
	
	protected function makeProcessContent(){
		$inputs = $this->series->getInputs();
		$class = $this->inputClass;
		$res = array();
		
		if ( count($inputs) == 0 ){
			$this->addClass('empty');
		}else{
			foreach( $inputs as $set ){
				$res[] = new $class( array_pop($set) );
			}
		}
		
		return array( 'inputs' => $res );
	}
	
	public function getJavascriptTemplate(){
		$class = $this->inputClass;
		$set = $this->series->makeSet('<%= this.set %>');
		
		$node = new $class( array_pop($set) );
		
		return new \Snap\Node\Ajax\Template( $node, $this->series->getUniqueness() );
	}
	
	public function getActions(){
		return array(
			new \Snap\Lib\Linking\Resource\Local('jquery/jquery.jqote2.js'),
			new \Snap\Lib\Linking\Resource\Local( $this, 'Form/Series/Stack.js'),
		);
	}
}