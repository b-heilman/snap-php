<?php

namespace Snap\Prototype\Comment\Node\View;

class Base extends \Snap\Node\Core\View {
	
	protected 
		$comment, 
		$deletion = null;
	
	protected function baseClass(){
		return 'comment-display';
	}
	
	protected function _finalize(){
		parent::_finalize();
		
		if ( $this->deletion ){
			$t = $this->deletion->getProcessResult();
			
			if ( !empty($t) ){
				$this->kill();
			}
		}
	}
	
	protected function getTemplateVariables(){
		$comment = new \Snap\Prototype\Comment\Lib\Element( $this->getStreamData()->get(0) );
		
		return array(
			'comment' => $comment,	
			'user'    => new \Snap\Prototype\User\Lib\Element( $comment->info(COMMENT_USER) ),
			'time'    => new \Snap\Lib\Core\Time( $comment->info('creation_date') )
		);
	}
}