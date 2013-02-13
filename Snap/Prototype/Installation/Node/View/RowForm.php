<?php

namespace Snap\Prototype\Installation\Node\View;

class RowForm extends \Snap\Node\View\Form {
	
	public function baseClass(){
		return 'prototype-row';
	}
	
	protected function getTemplateVariables(){
		$t = parent::getTemplateVariables();
		
		$t['prototype'] = $this->model->prototype;
		
		return $t;
	}
}