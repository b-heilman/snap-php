<?php

$this->append( 
	$this->login = new \Snap\Prototype\User\Node\Form\Access()
);

$this->append( new \Snap\Node\Controller\Navigation(array(
	'navVar'       => 'form',
	'outputStream' => 'form_nav'
)));

$this->append( new \Snap\Prototype\Installation\Node\View\Forms(array(
	'adminMode'   => true,
	'inputStream' => 'prototype_nav',
	'navStream'   => 'form_nav'
)) );

$this->append( new \Snap\Prototype\Installation\Node\View\Editor(array(
	'adminMode'       => true,
	'prototypeStream' => 'prototype_nav',
	'formStream'      => 'form_nav'
)) );

$this->append( new \Snap\Prototype\Installation\Node\Form\Management(array(
	'adminMode' => true
)) );

$this->append( new \Snap\Prototype\Installation\Node\Controller\Management(array(
	'adminMode'    => true,
	'inputStream'  => 'prototype_action',
	'outputStream' => 'install_messages'
)) );

$this->append( new \Snap\Node\View\Stacked(array(
	'inputStream' => 'install_messages'
)) );