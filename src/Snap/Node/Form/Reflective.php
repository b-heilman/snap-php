<?php

namespace Snap\Node\Form;

// TODO : for this to work, the element can't be broken down, since the reflection needs $el->inner, so watch when cacheing
abstract class Reflective extends \Snap\Node\Core\Block 
	implements \Snap\Node\Core\Actionable, \Snap\Node\Accessor\Reflective {
	
	protected
		$inSettings;
	
	protected function parseSettings( $settings = array() ){
		parent::parseSettings( $settings );
		
		$this->inSettings = $settings;
		$settings = $this->cleanSettings( $this->buildPairing($settings) + $settings ); // pairing overrides here
	
		$this->parseComponents( $settings );
	}
	
	/**
	 * @return array
	 */
	abstract function buildPairing();
	
	abstract function cleanSettings( $settings );
	
	protected function parseComponents( $settings ){
		if ( !isset($settings['view']) ){
			throw \Exception( get_class($this).' requires a view' );
		}
		
		if ( !isset($settings['control']) ){
			throw \Exception( get_class($this).' requires a control' );
		}
		
		$this->append( $settings['control'] );
		$this->append( $settings['view']);
	}
	
	protected function baseClass(){
		return 'form-reflective-wrapper';
	}
	
	protected function getAttributes(){
		$link = htmlentities( $this->page->fileManager->makeLink(new \Snap\Lib\File\Accessor\Reflective($this,$this->inSettings)) );
		return parent::getAttributes()." data-reflection=\"$link\"";
	}
	
	public function getActions(){
		return array(
				new \Snap\Lib\Linking\Resource\Local( $this )
		);
	}
}