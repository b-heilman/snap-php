<?php

use 
	\Snap\Node\Form\Element,
	\Snap\Node\Form\Input;

if ( isset($__messages) ){
	$this->append( $__messages );
}

$this->append( new Element(array(
	'input' => new Input\Text(array( 
		'input'  => $name
	)), 
	'label' => USER_LOGIN_LABEL
)) );
	
if ( isset($display) ){
	$this->append( new Element(array(
		'input' => new Input\Text(array( 
			'input'  => $display
		)), 
		'label' => USER_DISPLAY_LABEL
	)) );
}
	
$this->append( new Element(array(
	'input' => new Input\Password(array(
		'input'  => $password1
	)), 
	'label' => 'Password'
)) );

$this->append( new Element(array(
	'input' => new Input\Password(array(
		'input'  => $password2
	)),
	'label' => 'Password Again'
)) );

$this->append( new \Snap\Node\Form\Control(array(
	'buttons' => array( 'Create User' => 'submit', 'Reset' => 'reset')
)) );