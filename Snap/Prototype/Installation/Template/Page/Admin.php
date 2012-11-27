<?php

$this->append( new \Snap\Node\Controller\Navigation(array(
	'navVar'       => 'form',
	'outputStream' => 'form_nav'
)));

$this->append( new \Snap\Prototype\Installation\Node\View\Forms(array(
	'inputStream' => 'prototype_nav',
	'navStream'   => 'form_nav'
)) );

$this->append( new \Snap\Prototype\Installation\Node\View\Editor(array(
	'prototypeStream' => 'prototype_nav',
	'formStream'      => 'form_nav'
)) );

$this->append( new \Snap\Prototype\Installation\Node\Form\Management() );

$this->append( new \Snap\Prototype\Installation\Node\Controller\Management(array(
	'inputStream'  => 'prototype_action',
	'outputStream' => 'install_messages'
)) );

$this->append( new \Snap\Node\View\Stacked(array(
	'inputStream' => 'install_messages'
)) );