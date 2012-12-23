<?php

$this->append( new \Snap\Node\Controller\Navigation(array(
	'navVar'       => 'prototype',
	'outputStream' => 'prototype_nav'
)));

$this->append( new \Snap\Prototype\Installation\Node\Controller\Prototype(array(
	'outputStream' => 'prototypes'
)) );

$this->append( new \Snap\Node\View\Stacked(array(
	'class'        => 'management-prototype-list',
	'inputStream'  => 'prototypes',
	'primaryView'  => '\Snap\Prototype\Installation\Node\View\Prototype',
	'childStreams' => array(
		'prototype_nav' => 'navStream'
	)
)) );

$this->append( new \Snap\Node\Form\Control() );