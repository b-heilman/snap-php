Comment Testing
<?php

use \Snap\Prototype\User\Model\Doctrine\User;
use \Snap\Prototype\Comment\Model\Doctrine as Commenting;

$user = new User();
$name = 'User-'.time();

$user->setLogin( $name );
$user->setDisplay( $name );
$user->persist();

$thread = new Commenting\Thread();
$thread->setName('A comment');
$thread->setUser( $user );
$thread->persist();

$comment1 = new Commenting\Comment();
$comment1->setContent('woot woot');
$comment1->setUser( $user );
$comment1->setThread( $thread );
$comment1->persist();

$comment2 = new Commenting\Comment();
$comment2->setContent('woot woot');
$comment2->setUser( $user );
$comment2->setThread( $thread );
$comment2->setParent( $comment1 );
$comment2->persist();

$thread->flush();

echo 'User : '.$user->getId().' Thread : '.$thread->getId().'<br>';
