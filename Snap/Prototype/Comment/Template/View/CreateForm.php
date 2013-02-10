<?php
use \Snap\Node\Form\Input;

if ( $__messaging ){
	$this->append( $__messaging );
}

$this->append( new Input\Hidden(array(
	'input' => $thread
)) );

$this->append( new Input\Hidden(array(
	'input' => $parentComment
)) );

$this->append( new Input\Textarea(array(
	'name'  => $comment
)) );