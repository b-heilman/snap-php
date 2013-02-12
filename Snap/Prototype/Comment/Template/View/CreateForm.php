<?php
use \Snap\Node\Form\Input;

if ( $__messages ){
	$this->append( $__messages );
}

$this->append( new Input\Hidden(array(
	'input' => $thread
)) );

if ( isset($parentComment) ){
	$this->append( new Input\Hidden(array(
		'input' => $parentComment
	)) );
}

$this->append( new Input\Textarea(array(
	'input'  => $comment
)) );

$this->append( new \Snap\Node\Form\Control() );