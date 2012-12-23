<?php
namespace Snap\Node\Form;

$this->append( new Element(array(
	'label' => 'Title',
	'input' => new Input\Text(array(
		'name'     => 'name',
		'required' => true
	))
)) );

$this->append( new Element(array(
	'label' => 'File',
	'input' => new Input\File(array(
		'name'     => 'file',
		'required' => true
	))
)) );