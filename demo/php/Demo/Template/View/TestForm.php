<?php
if ( isset($__messages) ){
	$this->append( $__messages );
}

$this->append( new \Snap\Node\Form\Input\Basic(array(
	'type'  => 'text',
	'input' => $text
)) );

$this->append( new \Snap\Node\Form\Input\Basic(array(
	'type'  => 'text',
	'input' => $blankText
)) );

$this->append( new \Snap\Node\Form\Input\Basic(array(
	'type'  => 'password',
	'input' => $password
)) );

$this->append( new \Snap\Node\Form\Input\Textarea(array(
	'input' => $textarea
)) );

$this->append( new \Snap\Node\Form\Input\Checkbox(array(
	'input' => $uncheckbox
)) );

$this->append( new \Snap\Node\Form\Input\Checkbox(array(
	'input' => $checkbox
)) );

$this->append( new \Snap\Node\Form\Input\Select(array(
	'input' => $select
)) );

$this->append( new \Snap\Node\Form\Input\Select(array(
	'input' => $multiSelect
)) );

$this->append( new \Snap\Node\Form\Input\Pickable(array(
		'input' => $pickable
)) );

$this->append( new \Snap\Node\Form\Input\Pickable(array(
		'input' => $multipickable
)) );

$this->append( new \Snap\Node\Form\Input\File(array(
		'input' => $file
)) );

$this->append( new \Snap\Node\Form\Control() );