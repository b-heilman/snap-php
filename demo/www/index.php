<?php
require_once('Snap/Lib/Core/Bootstrap.php');

$router = new \Snap\Control\Router();

$router->addRoutes(array(
	'/'           => function(){ return new \Demo\Node\Page\Index(); },
	'Admin'       => function(){ return new \Snap\Prototype\Installation\Node\Page\Admin(); },
	'CommentTest' => function(){ return new \Demo\Node\View\CommentTest(); },
	'TopicTest'   => function(){ return new \Demo\Node\View\TopicTest(); }
));

$router->serve();