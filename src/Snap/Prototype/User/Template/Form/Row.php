<?php

use \Snap\Node\Form\Element;

$this->includeParentTemplate();

if ( !$prototype->installed ){
	$this->append( new Element(array(
		'input' => new \Snap\Node\Form\Input\Text($name),
		'label' => 'Admin'
	)) );

	$this->append( $this->password = new Element(array(
		'input' => new \Snap\Node\Form\Input\Password($password),
		'label' => 'Password'
	)) );
}