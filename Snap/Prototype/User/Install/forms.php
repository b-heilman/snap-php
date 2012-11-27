<?php

$forms = array(
	'New Admin' => array(
		'form' => '\Snap\Prototype\User\Node\Form\Create',
		'settings' => array(
			'admin' => true
		)
	),
	'New User' => '\Snap\Prototype\User\Node\Form\Create',
	'Manage' => '\Snap\Prototype\User\Node\Form\Management',
	'Unified' => array(
		'\Snap\Prototype\User\Node\Form\Create',
		'\Snap\Prototype\User\Node\Form\Management'
	)
);