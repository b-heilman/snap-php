<?php

use 
	\Snap\Node\Form\Element,
	\Snap\Node\Form\Input;

if ( isset($__messages) ){
	$this->append( $__messages );
}

$this->append( new Element(array(
	'input' => new Input\Text(array( 
		'input'  => $login
	)), 
	'label' => 'Login'
)) );
	
if ( isset($display) ){
	$this->append( new Element(array(
		'input' => new Input\Text(array( 
			'input'  => $display
		)), 
		'label' => 'Display'
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

$this->append( new \Snap\Node\Form\Control() );