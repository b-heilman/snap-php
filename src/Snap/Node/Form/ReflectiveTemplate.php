<?php

namespace Snap\Node\Form;

// TODO : for this to work, the element can't be broken down, since the reflection needs $el->inner, so watch when cacheing
abstract class Reflective extends \Snap\Node\Core\Template 
	implements \Snap\Node\Core\Actionable, \Snap\Node\Accessor\Reflective {
	
	protected
		$inSettings;
	
	protected function parseSettings( $settings = array() ){
		parent::parseSettings( $settings );
		
		$this->inSettings = $settings;
	}
	
	protected function baseClass(){
		return 'form-reflective-wrapper';
	}
	
	protected function getAttributes(){
		$link = htmlentities( $this->page->fileManager->makeLink(new \Snap\Lib\File\Accessor\Reflective($this,$this->inSettings)) );
		return parent::getAttributes()." data-reflection=\"$link\"";
	}
	
	public function getActions(){
		return array( new \Snap\Lib\Linking\Resource\Local($this, 'Form\Reflective.js') );
	}
}