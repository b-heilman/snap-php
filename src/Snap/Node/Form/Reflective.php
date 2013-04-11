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
		
		$this->parseComponents( $this->buildPairing($settings) );
	}
	
	/**
	 * @return array
	 */
	abstract function buildPairing( $settings );
	
	protected function parseComponents( $settings ){
		// TODO : allow auto generation if a model is passed in
		if ( !isset($settings['view']) ){
			throw \Exception( get_class($this).' requires a view' );
		}else{
			$view = $settings['view'];
			
			if ( is_callable($view) ){
				$view = $view();
			}
			
			if ( is_array($view) ){
				foreach( $view as $v ){
					$this->append( $v );
				}
			}else{
				$this->append( $view );
			}
		}
		
		if ( isset($settings['control']) ){
			$this->append( $settings['control'] );
		}
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
			new \Snap\Lib\Linking\Resource\Local($this, 'Ajax\Core.js'),
			new \Snap\Lib\Linking\Resource\Local($this, 'Form\Reflective.js')
		);
	}
}