<?php

// TODO : this is an artifact, but functionality I want to add
namespace Snap\Node\Page;

class Ajax extends \Snap\Node\Page\Basic {

	protected function build(){
		parent::build();
		
		$input = new \Snap\Lib\Form\Input();
		
		// TODO : holy hell I should be sanitizing this
		$class = $input->read( 'ajaxClass' ); 
		$vars = json_decode( $input->read('ajaxInit'), true );
		
		$this->append( new $class( $vars ) );
	}
	
	protected function getTranslator(){
		return null;
	}
	
	protected function getMeta(){
		return '';
	}
	
	protected function defaultTitle(){
		return 'Ajax Actions';
	}
}