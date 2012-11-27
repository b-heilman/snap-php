<?php
require_once('lib/snap/bootstrap.php');

indexing_organizer::addMapping( array(
	'_target' => new mvc_page(),
	'_title'  => 'Demo MV'
));

indexing_organizer::run();