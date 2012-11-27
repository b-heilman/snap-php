<?php

class main_view extends \Snap\Node\Template 
	implements styleable_local_node {
	
	public function getLocalCSS(){
		return '/views/main.css';
	}
	
	protected function _finalize(){
		if ( !users_current_proto::loggedIn() ){
			$el = $this->getElementsByClass('comments_creator_view_proto');
			if ( count($el) ){
				$el[0]->kill();
			}
		}
			
		parent::_finalize();
	}
}