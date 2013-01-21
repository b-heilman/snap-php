<?php

namespace Snap\Node\Core;

// TODO : move this somewhere better than core, or make as a trait
abstract class AjaxForm extends \Snap\Node\Core\Form 
	implements \Snap\Node\Core\Actionable {
	
	protected
		$storedSettings;
	
	public function __construct( $settings = array() ){
		$this->storedSettings = $settings;
		
		parent::__construct( $settings );
	}
	
	protected function getAttributes(){
    	return 'data-ajax-class="'.get_class($this).'" '
    		. 'data-ajax-init="'.htmlspecialchars( json_encode($this->storedSettings) ).'" '
    		. parent::getAttributes();
    }
	
	public function baseClass(){
		return 'ajax-form';
	}
	
	public function getActions(){
		return array(
			new \Snap\Lib\Linking\Resource\Local( $this->page,$this)
		);
	}
}
