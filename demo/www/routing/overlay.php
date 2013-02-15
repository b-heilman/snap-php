<?php

include_once('/Snap/Lib/Core/Bootstrap.php');

$router = new \Snap\Control\Router();

$router->addRoutes(array(
	'/' => new \Demo\Node\Page\Overlay(),
	'demo' => array(
		'/'      => new \Demo\Node\View\Routing\Home(),
		'check1' => function(){ return new \Demo\Node\View\Routing\Check1(); },
		'check2' => '\Demo\Node\View\Routing\Check2'
	)
));

$router->serve();