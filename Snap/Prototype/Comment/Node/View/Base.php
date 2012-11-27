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
}