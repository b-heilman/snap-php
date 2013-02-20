<?php

$forms = array(
	'New Admin' => function(){
		return new \Snap\Node\Form\Virtual(
				new \Snap\Prototype\User\Model\Form\Create( true )
		);
	},
	'New User' => function(){
		return new \Snap\Node\Form\Virtual(
				new \Snap\Prototype\User\Model\Form\Create()
		);
	},
	'Manage' => '\Snap\Prototype\User\Node\View\ManagementForm',
	'Unified' => array(
		function(){
			return new \Snap\Node\Form\Virtual(array(
				'model' => new \Snap\Prototype\User\Model\Form\Create(),
				'view'  => '\Snap\Prototype\User\Node\View\CreateForm'
			));
		},
		'\Snap\Prototype\User\Node\Form\Management'
	)
);