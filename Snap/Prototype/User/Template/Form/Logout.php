<?php
$ex = new \Exception();

$this->append( new \Snap\Node\Form\Input\Button(array(
	'type'  => 'submit', 
	'name'  => 'log_out',
	'value' => $logoutText
)), 'button' );