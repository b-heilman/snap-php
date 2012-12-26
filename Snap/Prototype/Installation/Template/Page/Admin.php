<?php

if ( $accessible ){
	$this->append( 
		$this->login = new \Snap\Prototype\User\Node\Form\Access()
	);
	
	$this->append( new \Snap\Node\Controller\Navigation(array(
		'navVar'       => 'form',
		'outputStream' => 'form_nav'
	)));
	
	$this->append( new \Snap\Prototype\Installation\Node\View\Forms(array(
		'deferTemplate' => $security,
		'inputStream'   => 'prototype_nav',
		'navStream'     => 'form_nav'
	)) );
	
	$this->append( new \Snap\Prototype\Installation\Node\View\Editor(array(
		'deferTemplate'   => $security,
		'prototypeStream' => 'prototype_nav',
		'formStream'      => 'form_nav'
	)) );
	
	$this->append( new \Snap\Prototype\Installation\Node\Form\Management(array(
		'deferTemplate' => $security
	)) );
	
	$this->append( new \Snap\Prototype\Installation\Node\Controller\Management(array(
		'deferTemplate' => $security,
		'inputStream'   => 'prototype_action',
		'outputStream'  => 'install_messages'
	)) );
	
	$this->append( new \Snap\Node\View\Stacked(array(
		'primaryView' => '\Snap\Node\View\Dump',
		'inputStream' => 'install_messages'
	)) );
}else{?> <h5>You need to set up the database</h5> <?php }
