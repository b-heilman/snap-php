<?php

use 
	\Snap\Node\Form\Element,
	\Snap\Node\Form\Input\Text;

$this->append( $r = new \Snap\Node\Form\Row() );	
	$r->append( new Element(array(
		'input' => new Text(array( 
			'name'  => USER_LOGIN
		)), 
		'label' => USER_LOGIN_LABEL
	)) );
	
	if ( USER_LOGIN != USER_DISPLAY ){
		$r->append( new Element(array(
			'input' => new Text(array( 
				'name'  => USER_DISPLAY
			)), 
			'label' => USER_DISPLAY_LABEL
		)) );
	}
	
	$r->append( new Element(array(
		'input' => new Text(array(  
			'name'  => USER_PASSWORD
		)), 
		'label' => 'Password'
	)) );
	
$this->append( $this->messaging );