<?php

namespace Snap\Prototype\Comment\Node\View;

class Creator extends \Snap\Node\View {
	
	protected 
		$form, 
		$threadVar;
	
	public function __construct( $settings = array() ){
		parent::__construct($settings);
		
		if ( !isset($settings['inputForm']) ){
			throw new \Exception('need a input form');
		}else{
			$this->form = $settings['inputForm'];
		}
		
		if ( !isset($settings['threadVar']) ){
			throw new \Exception('need a thread variable');
		}else{
			$this->threadVar = $settings['threadVar'];
		}
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'threadVar' => 'the variable to use for the thread',
			'inputForm' => 'the form to use for input'
		);
	}
	
	protected function setVariables(){
		$info = $this->getStreamData()->getPrimary();
		
		$form = new $this->form( array('messaging'=>true,'thread'=>$info[$this->threadVar]) );
		
		return array(
			'form' => $form
		);
	}
}