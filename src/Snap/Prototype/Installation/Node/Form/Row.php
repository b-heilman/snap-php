<?php

namespace Snap\Prototype\Installation\Node\Form;

class Row extends \Snap\Node\Core\Form {
	
	protected function baseClass(){
		return 'prototype-row';
	}
	
	protected function makeTemplateContent(){
		$t = parent::makeTemplateContent();
		
		$t['prototype'] = $this->model->prototype;
		
		return $t;
	}
}