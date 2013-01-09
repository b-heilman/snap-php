<?php

// TODO : this is an artifact, but functionality I want to add
namespace Snap\Node\Page;

class Ajax extends \Snap\Node\Page\Basic {

	public function build(){
		parent::build();
		
		
	}
	
	protected function getTranslator(){
		return null;
	}
	
	protected function getMeta(){
		return '';
	}
	
	protected function defaultTitle(){
		return 'Ajax Actions';
	}
}