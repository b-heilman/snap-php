<?php

use \Snap\Node\Form\Element;

if ( isset($__messages) ){
	$this->append( $__messages );
}

$this->append( $r = new \Snap\Node\Form\Row() );
	$r->append( new Element(array(
		'input' => new \Snap\Node\Form\Input\Text(array(
			'input' => $name
		)), 
		'label' => USER_LOGIN_LABEL
	)) );
    $r->append( new Element(array(
		'input' => new \Snap\Node\Form\Input\Password(array(
			'input' => $password
		)),
    	'label' => 'Password'
    )) );
    $r->append( new Element(array(
		'input' => new \Snap\Node\Form\Input\Button(array(
	    	'input' => new \Snap\Lib\Form\Input\Checkbox( 'button', 'submit' ),
	    	'text' => 'Login'
	    ))
    )) );