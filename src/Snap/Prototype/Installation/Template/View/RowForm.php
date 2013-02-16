<?php
$prototype = $this->model->prototype;

$this->append( new \Snap\Node\Form\Element(array(
	'input' => new \Snap\Node\Form\Input\Checkbox(array(
		'input'   => ${$prototype->name}
	)),
	'label' => $prototype->name
)), 'checkbox' );