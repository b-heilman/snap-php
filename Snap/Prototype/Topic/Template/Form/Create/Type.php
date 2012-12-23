<?php
$this->append( $r = new \Snap\Node\Form\Row() );
	$r->append( $this->topics_new_form_proto = new \Snap\Node\Form\Element(array(
		'input' => '\Snap\Node\Form\Input\Text', 
		'name'  => 'topics_type_new_name', 
		'label' => 'Topic Type'
	)) );