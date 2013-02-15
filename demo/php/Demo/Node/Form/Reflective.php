<?php

namespace Demo\Node\Form;

class Reflective extends \Snap\Node\Form\Reflective {
	
	public function cleanSettings( $settings ){
		return $settings;
	}
	
	public function buildPairing(){
		$model = new \Demo\Model\Form\TestForm();
		$control = new \Demo\Control\Feed\TestForm( $model );
		$view = new \Demo\Node\View\TestForm( $model );
				
		return array(
			'model'   => $model,
			'control' => $control,
			'view'    => $view
 		);
	}
}