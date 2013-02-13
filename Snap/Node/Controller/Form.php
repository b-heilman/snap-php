<?php

namespace Snap\Node\Controller;

abstract class Form extends \Snap\Node\Core\Controller {
	
	protected
		$model;
	
	public function __construct( $settings = array() ){
		if ( !is_array($settings) ){
			$settings = array( 'model' => $settings );
		}
		
		parent::__construct( $settings );
	}
	
	protected function parseSettings( $settings = array() ){
		if ( isset($settings['model']) ){
			$this->model = $settings['model'];
		}
		
		/* @var $this->model \Snap\Model\Form */
		if ( !($this->model instanceof \Snap\Model\Form) ){
			throw new \Exception("A form's model needs to be instance of \Snap\Model\Form");
		}
		
		parent::parseSettings( $settings );
	}
	
	protected function makeData(){
		if ( $this->model->wasFormSubmitted() ){
			$proc = $this->model->getResults();
		
			if ( $proc->hasErrors() ){
				return new \Snap\Lib\Mvc\Data( null ); // pre processing errors
			}else{
				$rtn = $this->processInput( $proc );
				
				if ( $proc->hasErrors() ){
					return new \Snap\Lib\Mvc\Data( null ); // post processing errors
				}else{
					return new \Snap\Lib\Mvc\Data( $rtn );
				}
			}
		}else{
			return new \Snap\Lib\Mvc\Data( null ); // model wasn't even submitted
		}
	}
	
	abstract protected function processInput( \Snap\Lib\Form\Result $formData );
}