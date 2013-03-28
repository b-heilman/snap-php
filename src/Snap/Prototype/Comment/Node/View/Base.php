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
		$format = $this->getConfig('//Format');
		
		$info = array(
			'comment'   => $comment,	
			'user'      => $comment->getUser()->getDisplay(),
			'date'      => $comment->getCreationDate()->format( $format->date ),
			'time'      => $comment->getCreationDate()->format( $format->time ),
			'timestamp' => $comment->getCreationDate()->format( $format->timestamp )
		);
		
		if ( \Snap\Prototype\User\Lib\Current::isAdmin() ){
			$model = new \Snap\Prototype\Comment\Model\Form\Delete( $comment );
			
			$info['delete'] = new \Snap\Prototype\Comment\Node\Form\Delete(array('model' => $model));
			$info['control'] = new \Snap\Prototype\Comment\Control\Form\Delete(array('model' => $model));
		}
		
		return $info;
	}
}