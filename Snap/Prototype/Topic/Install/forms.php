<?php
$forms = array(
	'New Topic Type' => '\Snap\Prototype\Topic\Node\Form\Create\Type',
	'New Topic'      => '\Snap\Prototype\Topic\Node\Form\Create',
	'Manage Topics'  => array(
		'\Snap\Prototype\Topic\Node\Form\Create\Type',
		'\Snap\Prototype\Topic\Node\Form\Create'
	)
);