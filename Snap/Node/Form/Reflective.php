<?php

namespace Snap\Node\Form;

// TODO : maybe not really virtual ?
abstract class Reflective extends \Snap\Node\Core\Block 
	implements \Snap\Node\Accessor\Reflective {
	
	protected
		$inSettings;
	
	protected function parseSettings( $settings = array() ){
		parent::parseSettings( $settings );
		
		$settings = $this->buildPairing() + $settings; // pairing overrides here
		
		$this->parseComponents( $settings );
	}
	
	/**
	 * @return array
	 */
	abstract function buildPairing();
	
	protected function parseComponents( $settings ){
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
		
		$controllerSettings = isset($settings['controllerSettings']) ? $settings['controllerSettings'] : array();
		$viewSettings = isset($settings['viewSettings']) ? $settings['viewSettings'] : array();
		
		$inputStream = isset($settings['inputStream']) ? $settings['inputStream'] : null;
		$outputStream = isset($settings['outputStream']) ? $settings['outputStream'] : $controller;
		
		$this->append(new $controller( $controllerSettings + array(
			'model'        => $model,
			'outputStream' => $outputStream
		)));
		
		$this->append(new $view( $viewSettings + array(
			'model'       => $model,
			'inputStream' => $inputStream
		)));
	}
	
	protected function baseClass(){
		return 'form-reflective-wrapper';
	}
	/*
	public function makeAjaxLink( $class, $data ){
 		return $this->fileManager->makeLink( new \Snap\Lib\File\Accessor\Ajax($class,$data) );
 	}
	*/
}