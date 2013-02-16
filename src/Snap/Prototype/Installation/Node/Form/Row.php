<?php

namespace Snap\Prototype\Installation\Node\Form;

class Row extends \Snap\Node\Core\Form {
	
	public function baseClass(){
		return 'prototype-row';
	}
	
	protected function makeTemplateContent(){
		$t = parent::makeTemplateContent();
		
		$t['prototype'] = $this->model->prototype;
		
		return $t;
	}
}