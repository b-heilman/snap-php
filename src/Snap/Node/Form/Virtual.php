<?php

namespace Snap\Node\Form;

// TODO : extend this virtual idea to the core level, so you can create logical groups without DOM elements
class Virtual extends \Snap\Node\Core\Block {
	
	public function __construct( $settings = array() ){
		if ( !is_array($settings) ){
			$settings = array( 'model' => $settings );
		}
		
		parent::__construct( $settings );
	}
	
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
		
		$view = isset($settings['view']) ? $settings['view'] : str_replace('Model', 'Node', get_class($model));
		$control = isset($settings['control']) ? $settings['control'] : str_replace('Node', 'Control', $view);
		
		$controlSettings = isset($settings['controlSettings']) ? $settings['controlSettings'] : array();
		$viewSettings = isset($settings['viewSettings']) ? $settings['viewSettings'] : array();
		
		$inputStream = isset($settings['inputStream']) ? $settings['inputStream'] : null;
		$outputStream = isset($settings['outputStream']) ? $settings['outputStream'] : get_class($control);
		
		$this->append(new $control( $controlSettings + array(
			'model'        => $model,
			'outputStream' => $outputStream
		)));
		
		$this->append(new $view( $viewSettings + array(
			'model'       => $model,
			'inputStream' => $inputStream
		)));
	}
	
	public function html(){
		return $this->inner();
	}
}