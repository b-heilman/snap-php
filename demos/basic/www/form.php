<?php
require_once('lib/snap/bootstrap.php');

indexing_organizer::addMapping( array(
	'_target' => new form_page(),
	'_title'  => 'Form Sample'
));

indexing_organizer::run();