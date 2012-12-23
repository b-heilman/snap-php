<?php

use \Snap\Node\Form\Element;

$this->append( $this->messaging );
	
$this->append( $r = new \Snap\Node\Form\Row() );
	$r->append( new Element(array(
		'input' => new \Snap\Node\Form\Input\Text(array(
			'name'  => '_login', 
			'label' => USER_LOGIN_LABEL
		))
	)) );
    $r->append( new Element(array(
		'input' => new \Snap\Node\Form\Input\Password(array(
			'name'  => '_passwrd', 
			'label' => 'Password'
		))
    )) );
    $r->append( new Element(array(
		'input' => new \Snap\Node\Form\Input\Button(array(
	    	'type'  => 'submit', 
	    	'name'  => 'log_in',
	    	'value' => 'Login'
	    ))
    )) );