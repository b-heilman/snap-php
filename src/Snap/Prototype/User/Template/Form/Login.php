<?php

use \Snap\Node\Form\Element;

if ( isset($__messages) ){
	$this->append( $__messages );
}

$this->append( new Element(array(
	'input' => new \Snap\Node\Form\Input\Text( $login ), 
	'label' => 'Login'
)) );
	
$this->append( new Element(array(
	'input' => new \Snap\Node\Form\Input\Password( $password ),
	'label' => 'Password'
)) );