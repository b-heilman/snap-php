<?php

namespace Snap\Control;

abstract class Form extends \Snap\Control\Feed {
	
	protected
	/** 
	 * @var \Snap\Model\Form 
	 **/
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
				try {
					$rtn = $this->processInput( $proc );
				}catch( \Exception $ex ){
					$proc->addFormError( 'Form unable to be processed' );
					$proc->addDebug( $ex->getMessage() );
				}
				
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