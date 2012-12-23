<?php

use \Snap\Node\Form\Element;

$this->append( new Element(array(
	'input' => new \Snap\Node\Form\Input\Checkbox(array(
		'name'    => $prototype->name, 
		'value'   => 1,
		'checked' => $prototype->installed,
		'type'    => 'checkbox'
	)),
	'label' => $prototype->name
)), 'checkbox' );

if ( !$prototype->installed ){
	$this->append( new Element(array(
		'input' => new \Snap\Node\Form\Input\Text(array(
			'name'     => 'install_user',
			'required' => true
		)),
		'label' => 'Admin'
	)) );

	$this->append( $this->password = new Element(array(
		'input' => new \Snap\Node\Form\Input\Password(array(
			'name'     => 'install_pwd',
			'required' => true
		)),
		'label' => 'Password'
	)) );
}