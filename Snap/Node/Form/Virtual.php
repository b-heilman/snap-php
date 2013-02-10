<?php

namespace Snap\Node\Form;

// TODO : extend this virtual idea to the core level, so you can create logical groups without DOM elements
class Virtual extends \Snap\Node\Core\Block {
	
	protected function parseSettings( $settings = array() ){
		$settings['tag'] = 'junk';
		
		parent::parseSettings( $settings );
		
		if ( isset($settings['model']) ){
			$model = $settings['model'];
			
			if ( is_string($model) ){
				$model = $model();
			}
		}else{
			throw \Exception( get_class($this).' requires a model' );
		}
		
		// TODO : can I assume the view off the model if it is not suplied?
		if ( isset($settings['view']) ){
			$view = $settings['view'];
		}else{
			throw \Exception( get_class($this).' requires a view' );
		}
		
		$controller = isset($settings['controller']) ? $settings['controller'] : str_replace('View', 'Controller', $view);
		$inputStream = isset($settings['inputStream']) ? $settings['inputStream'] : null;
		$outputStream = isset($settings['outputStream']) ? $settings['outputStream'] : $controller;
		
		$this->append(new $controller(array(
			'model'        => $model,
			'outputStream' => $outputStream
		)));
		
		$this->append(new $view(array(
			'model'       => $model,
			'inputStream' => $inputStream
		)));
	}
	
	public function html(){
		return $this->inner();
	}
}