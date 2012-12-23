<?php
$this->append( $this->table = new \Snap\Node\Form\Table(array(
	'name'    => 'topic_data', 
	'data'    => \Snap\Prototype\Topic\Lib\Element::data(),
       'hidden'  => $this->hidden,
	'headers' => $this->fields,
	'types'   => $this->types
	)
));