<?php

namespace Snap\Prototype\Comment\Node\View;

class Base extends \Snap\Node\View {
	
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
		return array(
			'comment' => new \Snap\Prototype\Comment\Lib\Element( $this->getStreamData()->get(0) ),	
			'user'    => new \Snap\Prototype\User\Lib\Element( $comment->info(COMMENT_USER) ),
			'time'    => new \Snap\Lib\Core\Time( $comment->info('creation_date') )
		);
	}
}