<?php

namespace Snap\Prototype\User\Node\Form;

class Row extends \Snap\Prototype\Installation\Node\Form\Row {
	// no additions, just registering
	
	protected function baseClass(){
		return parent::baseClass().' install-user-row';
	}
}