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
		
		error_log( 'kill comment' );
		if ( !$this->comment->initialized() ){
			$this->kill();
		}
	}
	
	protected function getTemplateVariables(){
		$this->comment = new \Snap\Prototype\Comment\Lib\Element( $this->getStreamData()->get(0) );
		
		return array(
			'comment' => $this->comment,	
			'user'    => new \Snap\Prototype\User\Lib\Element( $this->comment->info(COMMENT_USER) ),
			'time'    => new \Snap\Lib\Core\Time( $this->comment->info('creation_date') )
		);
	}
}