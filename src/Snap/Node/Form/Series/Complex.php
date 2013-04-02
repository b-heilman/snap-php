<?php

namespace Snap\Node\Form\Series;

abstract class Complex extends \Snap\Node\Core\Template 
	implements \Snap\Node\Actionable\Template, \Snap\Node\Actionable\Templatable, \Snap\Node\Core\Actionable {
	
	protected
		$series;
	
	public function __construct( $settings = array() ){
		if ( $settings instanceof \Snap\Lib\Form\Series ){
			$settings = array( 'series' => $settings );
		}
		
		parent::__construct( $settings );
	}
	
	protected function parseSettings( $settings = array() ){
		$this->series = $settings['series'];
		
		parent::parseSettings( $settings );
	}
	
	public function build(){
		parent::build();
		
		$this->parent->append( new \Snap\Node\Form\Input\Hidden($this->series->getControl()) );
	}
	
	protected function baseClass(){
		return 'form-series-complex';
	}
	
	protected function loadTemplate( $__template ){
		$inputs = $this->series->getInputs();
		
		if ( count($inputs) == 0 ){
			$this->addClass('empty');
		}else{
			foreach( $inputs as $set ){
				echo '<'.$this->getWrapper().'>';
				$this->setTemplateData( $set );
				parent::loadTemplate( $__template );
				echo '</'.$this->getWrapper().'>';
			}
		}
	}
	
	protected function getAttributes(){
		return parent::getAttributes()." data-snap-template=\"{$this->series->getUniqueness()}\"";
	}
	
	public function getWrapper(){
		return 'fieldset';
	}
	
	public function getPath(){
		return $this->path;
	}
	
	public function getJavascriptTemplate(){
		return new \Snap\Node\Ajax\Template( $this, $this->series->getUniqueness(), $this->series->makeSet('<%= this.set %>') );
	}
	
	public function getActions(){
		return array(
			new \Snap\Lib\Linking\Resource\Local('jquery/jquery.jqote2.js'),
			new \Snap\Lib\Linking\Resource\Local( $this, 'Form/Series/Complex.js'),
		);
	}
}