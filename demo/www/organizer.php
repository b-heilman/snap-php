<?php

use
\Snap\Prototype\Indexing\Lib\Organizer;

require_once('Snap/Lib/Core/Bootstrap.php');

Organizer::addMapping( array(
		'_target' => new \Demo\Node\Page\Organize(array(
				'data' => false
		)),
		'_title'  => 'Overriden Title',
		'_class'  => '\Demo\Node\View\Main'
));

Organizer::run();