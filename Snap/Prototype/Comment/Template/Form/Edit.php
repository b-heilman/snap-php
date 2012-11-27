<?php

$this->append( new \Snap\Node\Form\Input\Hidden(array(
	'name'  => 'comment',
	'value' => $this->comment->id()
)) );
	
$this->append( new \Snap\Node\Form\Input\Textarea(array('name' => 'comment')) );

$this->append( new \Snap\Node\Form\Control(array('name' => $this->id.'_buttons')) );