<?php

namespace Snap\Prototype\Comment\Node\Form;

class Delete extends \Snap\Node\Core\ProducerForm {

	protected 
		$comment = null;
	
	public function __construct( $settings = array() ){
		if ( !isset($settings['data']) ){
			throw new \Exception('data is a required setting for '.get_class($this));
		}
		
		$this->comment = new \Snap\Prototype\Comment\Lib\Element( $settings['data'] );
		
		unset( $settings['data'] );
									
		$settings['id'] = 'remove_comment_'.$this->comment->id();
		
		parent::__construct($settings);
	}
	
	public static function getSettings(){
		return parent::getSettings() + array( 
			'data' => 'the data passed to the comment constructor'
		);
	}
	
	public function getOuputStream(){
		return 'remove_comment';
	}
	
	protected function processInput( \Snap\Lib\Form\Result &$formData ){
		$res = null;
		
		if ( $formData->hasInput('remove_comment') ){
			$res = $this->comment;
			if ( !$this->comment->delete() ){
				$res = null;
			}
		}
		
		return $res;
	}
}