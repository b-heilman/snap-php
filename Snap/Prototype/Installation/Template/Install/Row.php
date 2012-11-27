<?php

$this->append( new \Snap\Node\Form\Element(array(
	'input' => new \Snap\Node\Form\Input\Checkbox(array(
		'name'    => $prototype->name, 
		'value'   => 1,
		'checked' => $prototype->installed,
		'type'    => 'checkbox'
	)),
	'label' => $prototype->name
)), 'checkbox' );