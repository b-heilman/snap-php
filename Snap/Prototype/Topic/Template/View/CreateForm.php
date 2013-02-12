<?php

if ( isset($__messages) ){
	$this->append( $__messages );
}

		
$this->append( new \Snap\Node\Form\Element(array(
	'input' => new \Snap\Node\Form\Input\Text(array(
		'input'  => $title
	)), 
	'label' => 'Title'
)) );
	
if ( isset($type) ){
	$this->append( $this->select = new \Snap\Node\Form\Element(array(
		'input' => new \Snap\Node\Form\Input\Select(array( 
			'input' => $type/*,
			TODO : did I do this?
			'blockValue' => '',
			'options'    => \Snap\Prototype\Topic\Lib\Type::hash()
			*/
		)),
		'label' => 'Type'
	)) );
}

$this->append( new \Snap\Node\Form\Input\Textarea(array('input' => $content)) );

$this->append( new \Snap\Node\Form\Control() );