<?php

namespace Snap\Node\Form\Input;

// TODO it would be nice to have a listing object that isn't a view... instead a template... but
//----
// TODO - come up with standard way to bind templates to objects 
class Listing extends \Snap\Node\View\Listing 
	implements \Snap\Node\Actionable\Template, \Snap\Node\Actionable\Templatable, \Snap\Node\Core\Actionable {
	
	protected
		$input;
	
	public function __construct( $settings = array() ){
		if ( $settings instanceof \Snap\Lib\Form\Input ){
			$settings = array( 'input' => $settings );
		}
	
		parent::__construct( $settings );
	}
	
	protected function parseSettings( $settings = array() ){
		if ( !isset($settings['input']) ){
			throw new \Exception('A '.get_class($this).' needs an input');
		}
		
		$this->input = $settings['input'];
		/* @var $this->content \Snap\Model\Form */
		if ( !($this->input instanceof \Snap\Lib\Form\Input\Listing) ){
			throw new Exception("A form's content needs to be instance of \Snap\Lib\Form\Input\Listing");
		}
	
		parent::parseSettings( $settings );
	}
	
	protected function baseClass(){
		return 'input-listing';
	}
	
	protected function getStreamData( $stream = null ){
		return new \Snap\Lib\Mvc\Data\Collection( $this->input->getInstances() );
	}
	
	protected function parseListData( $in ){
		return array(
			'input'   => $this->input,
			'display' => $in->getDisplay(),
			'value'   => $in->getValue()
		);
	}
	
	protected function getAttributes(){
		return parent::getAttributes()." data-snap-template=\"{$this->input->getName()}\"";
	}
	
	public function getWrapper(){
		return 'li';
	}
	
	public function getPath(){
		return $this->path;
	}
	
	public function getJavascriptTemplate(){
		return new \Snap\Node\Ajax\Template( $this, $this->input->getName(), array(
			'input'   => $this->input,
			'display' => '<%= this.display %>',
			'value'   => '<%= this.value %>'
		) );
	}
	
	public function getActions(){
		return array( 
			new \Snap\Lib\Linking\Resource\Local('jquery/jquery.jqote2.js'),
			new \Snap\Lib\Linking\Resource\Local( $this, 'Form/Input/Listing.js')
		);
	}
}