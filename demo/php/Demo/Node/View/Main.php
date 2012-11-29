<?php

namespace Demo\Node\View;

class Main extends \Snap\Node\Template 
	implements \Snap\Node\Styleable {
	
	public function getStyles(){
		return array(
			new \Snap\Lib\Linking\Resource\Local($this)
		);
	}
	
	protected function _finalize(){
		if ( !\Snap\Prototype\User\Lib\Current::loggedIn() ){
			$el = $this->getElementsByClass('\Snap\Prototype\Comment\Node\View\Creator');
			if ( count($el) ){
				$el[0]->kill();
			}
		}
			
		parent::_finalize();
	}
}