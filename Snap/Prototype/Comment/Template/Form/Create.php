<?php
use \Snap\Node\Form\Input;

$this->append( $this->messaging );

$this->append( $this->thread = new Input\Hidden(array(
	'name'  => 'thread', 
	'value' => $this->thread
)) );

$this->append( $this->parentComment = new Input\Hidden(array(
	'name'  => 'parent', 
	'value' => $this->parentComment
)) );

$this->append( new Input\Textarea(array('name' => 'comment')) );