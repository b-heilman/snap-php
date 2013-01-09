<?php

namespace Snap\Node;

abstract class AjaxForm extends \Snap\Node\Form 
	implements \Snap\Node\Actionable {
	
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
