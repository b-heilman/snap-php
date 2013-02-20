<?php
require_once('Snap/Lib/Core/Bootstrap.php');

$router = new \Snap\Control\Router();

$router->addRoutes(array(
	'/'           => new \Demo\Node\Page\Index(),
	'Admin'       => new \Snap\Prototype\Installation\Node\Page\Admin(),
	'CommentTest' => new \Demo\Node\View\CommentTest()
));

$router->serve();