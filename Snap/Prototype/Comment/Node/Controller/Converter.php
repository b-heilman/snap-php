<?php

namespace Snap\Prototype\Comment\Node\Controller;

class Converter extends \Snap\Node\Controller\Converter {

	protected 
		$variable, 
		$comment_thread;
	
	public function __construct( $settings = array() ){
		parent::__construct($settings);
		
		if ( isset($settings['inputVariable']) ){
			$this->variable = $settings['inputVariable'];
		}else{
			throw new \Exception("Need an inputVariable");
		}
	} 
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'inputVariable'    => 'the variable of the stream to convert'
		);
	}
	
	protected function makeData(){
		$ctrl = $this->input;
		
		if ( $ctrl->hasVar('active') ){
			$info = $ctrl->get( $ctrl->getVar('active') );
		}elseif( $ctrl->data->count() > 0 ){
			$info = $ctrl->get( 0 );
		}else{
			throw new \Exception("This is broke");
		}
		
		$this->comment_thread = $info[$this->variable];
		
		$ctrl = new \Snap\Lib\Mvc\Control( $this->factory, new \Snap\Lib\Mvc\Data(
			\Snap\Prototype\Comment\Lib\Element::data(
				array(COMMENT_THREAD_ID => $this->comment_thread)
			)
		));
		
		return $ctrl;
	}
}