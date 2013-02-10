<?php

namespace Snap\Prototype\Installation\Node\View;

class Row extends \Snap\Node\View\Form {
	
	public function baseClass(){
		return 'prototype-row';
	}
	
	protected function getTemplateVariables(){
		$t = parent::getTemplateVariables();
		
		$t['prototype'] = $this->content->prototype;
		
		return $t;
	}
}