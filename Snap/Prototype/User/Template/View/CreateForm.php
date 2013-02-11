<?php

use 
	\Snap\Node\Form\Element,
	\Snap\Node\Form\Input\Text;

$this->append( $r = new \Snap\Node\Form\Row() );	
	$r->append( new Element(array(
		'input' => new Text(array( 
			'input'  => $name
		)), 
		'label' => USER_LOGIN_LABEL
	)) );
	
	if ( isset($display) ){
		$r->append( new Element(array(
			'input' => new Text(array( 
				'input'  => $display
			)), 
			'label' => USER_DISPLAY_LABEL
		)) );
	}
	
	$r->append( new Element(array(
		'input' => new Text(array(  
			'input'  => $password
		)), 
		'label' => 'Password'
	)) );
	
	$r->append( new Element(array(
		'input' => new Text(array(
			'input'  => $password2
		)),
		'label' => 'Password Again'
	)) );
	
$this->append( $this->messaging );