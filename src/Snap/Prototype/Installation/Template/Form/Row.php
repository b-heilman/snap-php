<?php

$this->append( new \Snap\Node\Form\Element(array(
	'input' => new \Snap\Node\Form\Input\Checkbox($proto),
	'label' => $prototype->name
)), 'checkbox' );