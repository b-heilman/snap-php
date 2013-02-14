<?php

include_once('/Snap/Lib/Core/Bootstrap.php');

$router = new \Snap\Control\Router();

$router->addRoutes(array(
	'/' => new \Demo\Node\Page\Index(),
	'demo' => array(
		'/'      => new \Demo\Node\View\Routing\Home(),
		'/page'  => new \Demo\Node\Page\Routing(),
		'check1' => function(){ return new \Demo\Node\View\Routing\Check1(); },
		'check2' => '\Demo\Node\View\Routing\Check2'
	)
));

$router->serve();