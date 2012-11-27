<?php
require_once('lib/snap/bootstrap.php');

indexing_organizer::addMapping( array(
	'_target' => new index_page(),
	'_title'  => 'Demo Basic'
));

indexing_organizer::run();