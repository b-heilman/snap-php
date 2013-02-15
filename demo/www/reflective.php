<?php

require_once('Snap/Lib/Core/Bootstrap.php');

$page = new \Snap\Node\Page\Basic();

$page->append( new \Demo\Node\Form\Reflective() );

$page->serve();