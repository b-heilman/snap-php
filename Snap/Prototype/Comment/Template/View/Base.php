<?php

$header = new \Snap\Node\Core\Block(array(
	'tag'   => 'div', 
	'class' => 'comment-view-header'
));

$header->write( $user->name(), 'comment-user' );
	
$header->write( 'written '.$time->maxSince().' ago', 'comment-time' );

$this->append( $header );
//-------

$content = new \Snap\Node\Core\Block(array(
	'tag' => 'div',
	'class' => 'comment-view-content'
));

$content->append( new \Snap\Node\Core\Text(array(
	'tag' => 'pre', 
	'text' => $comment->info('content')
)) );
		
$this->append( $content );
//-------

$footer = new \Snap\Node\Core\Block(array(
	'tag'   => 'span',
	'class' => 'comment-view-footer'
));

if ( \Snap\Prototype\User\Lib\Current::isAdmin() ){
	$footer->append( new \Snap\Prototype\Comment\Node\Form\Delete(array('data' => $comment)) );
}
		
$this->append( $footer );