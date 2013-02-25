<?php

if ( isset($__messages) ){
	$this->append( $__messages );
}

$this->append( new \Snap\Node\Form\Element(array(
	'input' => new \Snap\Node\Form\Input\Text( $name ), 
	'label' => 'Title'
)) );

if ( isset($type) ){
	$this->append( $this->select = new \Snap\Node\Form\Element(array(
		'input' => new \Snap\Node\Form\Input\Select( $type ),
		'label' => 'Type'
	)) );
}

$this->append( new \Snap\Node\Form\Control() );