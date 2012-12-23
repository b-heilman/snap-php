<?php
$this->append( $this->table = new \Snap\Node\Form\Table(array(
	'name'    => 'user_data', 
	'data'    => \Snap\Prototype\User\Lib\Element::data(),
	'hidden'  => $hidden,
	'headers' => $fields,
	'types'   => $types
)) );