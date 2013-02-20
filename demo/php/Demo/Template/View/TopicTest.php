Comment Testing
<?php

use \Snap\Prototype\User\Model\Doctrine\User;
use \Snap\Prototype\Comment\Model\Doctrine as Commenting;
use \Snap\Prototype\Topic\Model\Doctrine as Topics;

$user = new User();
$name = 'User-'.time();

$user->setLogin( $name );
$user->setDisplay( $name );
$user->persist();

$thread = new Commenting\Thread();
$thread->setName('A comment');
$thread->setUser( $user );
$thread->persist();

$type = new Topics\Type();
$type->setName( 'type-'.time() );
$type->persist();

$topic = new Topics\Topic();
$topic->setName( 'topic-'.time() );
$topic->setType( $type );
$topic->setCommentThread( $thread );
$topic->persist();

$thread->flush();

echo 'Topic : '.$topic->getId().' Thread : '.$thread->getId().'<br>';
