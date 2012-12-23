<?php

namespace Snap\Prototype\Comment\Node\Form;

class Edit extends \Snap\Node\Form {

	protected 
		$comment = null,
		$editor = null,
		$view = null;
	
	public function __construct( $settings = array() ){
		if ( isset($settings['data']) ){
			$this->setComment( $settings['data'] );
		}
		
		parent::__construct( $settings );
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'data' => 'the data passed to the comments_element_proto\'s constructor'
		);
	}
	
	public function setComment( $data ){
		$this->comment = new \Snap\Prototype\Comment\Lib\Element( $data );
	}
	
	protected function processInput( $formData  ){
		if ( $formData = parent::_process($data) ){
			if ( $formData->hasChanged('comment') ){
				$info = array(
					COMMENT_THREAD_ID => $formData->getValue('commentThread'),
					COMMENT_USER => users_current_proto::getUser()->id(),
					'content' => $formData->getValue('comment')
				);
				
				if ( $formData->hasChanged('commentParent') ){
					$info[COMMENT_PARENT] = $formData->getValue('commentParent');
				}
				
				if ( \Snap\Prototype\Comment\Lib\Element::create($info) ){
					$this->prepend( new \Snap\Node\Text('comment created') );
				}else{
					$this->prepend( new \Snap\Node\Text(array(
						'text'  => 'Error creating comment',
						'class' => 'error'
					)) );
				}
			}else{
				$this->prepend( new \Snap\Node\Text(array(
					'text'  => 'You comment was blank',
					'class' => 'error'
				)) );
			}
		}
	}
}