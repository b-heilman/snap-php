<?php

namespace Snap\Prototype\Comment\Node\View;

class Base extends \Snap\Node\Core\View {
	
	protected 
		$comment;
	
	protected function baseClass(){
		return 'comment-display';
	}
	
	protected function _finalize(){
		parent::_finalize();
		
		if ( !$this->comment->initialized() ){
			$this->kill();
		}
	}
	
	protected function makeProcessContent(){
		/** @var \Snap\Prototype\Comment\Model\Doctrine\Comment */
		$this->comment = $comment = $this->getStreamData()->get(0);
		
		return array(
			'comment' => $comment,	
			'user'    => $comment->getUser()->getDisplay(),
			'time'    => $comment->getCreationTime()->format( 'm-d-Y H:i:s' )
		);
	}
}