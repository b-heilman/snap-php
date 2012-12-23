<?php

namespace Snap\Prototype\Comment\Node\Form;

use \Snap\Prototype\User\Lib\Current;

class Create extends \Snap\Node\ProducerForm {

	protected 
		$parentComment = null,
		$thread = null;
	
	public function __construct( $settings = array() ){
		parent::__construct( $settings );
		
		if ( isset($settings['thread']) ){
			$this->setThread( $settings['thread'] );
		}
		
		if ( isset($settings['parentComment']) ){
			$this->setParentComment( $settings['parentComment'] );
		}
	}
	
	public function getOuputStream(){
		return 'new_comment';
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'thread'        => 'the comment thread\'s id',
			'parentComment' => 'the parent id of the comment'
		);
	}
	
	public function setThread( $id ){
		if ( is_object($this->thread) ){
			$this->thread->setDefaultValue( $id );
		}else{
			$this->thread = $id;
		}
	}
	
	public function setParentComment( $id ){
		if ( is_object($this->parentComment) ){
			$this->parentComment->setDefaultValue( $id );
		}else{
			$this->parentComment = $id;
		}
	}
	
	protected function sanitizeComment( $comment ){
		return str_replace("\n", '<br>', htmlentities($comment) );
	}
	
	protected function processInput( \Snap\Lib\Form\Data\Result &$formData ){
		$res = null;
		
		if ( $formData->hasChanged('comment') && Current::loggedIn() ){
			$info = array(
				COMMENT_THREAD_ID => $formData->getValue('thread'),
				COMMENT_USER => Current::getUser()->id(),
				'content' => $this->sanitizeComment( $formData->getValue('comment') )
			);
			
			if ( $formData->hasChanged('parent') ){
				$info[COMMENT_PARENT] = $formData->getValue('parent');
			}
			
			if ( $id = \Snap\Prototype\Comment\Lib\Element::create($info) ){
				$res = new \Snap\Prototype\Comment\Lib\Element( $id );
				
				$this->addNote( 'Comment Created' );
				$this->reset();
			}else{
				$this->prepend( $notes = new \Snap\Node\Block(array(
					'tag'  => 'span', 
					'class' => 'errors'
				)) );
				
				$formData->addError( 'Error creating comment' );
			}
		}else{
			$formData->addError( 'Your comment was blank' );
		}
		
		return $res;
	}
}