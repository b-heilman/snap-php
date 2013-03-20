<?php

$this->append( new \Snap\Node\Form\Element(array(
		'input' => new \Snap\Node\Form\Input\Autocomplete( array(
				'input' => $name,
				'acSettings' => array(
					'ignoreValue' => ''
				)
		)),
		'label'	=> 'Add Tag'
)) );