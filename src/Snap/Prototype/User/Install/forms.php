<?php

$forms = array(
	'New Admin' => function(){
		return new \Snap\Node\Form\Virtual(array(
			'model' => new \Snap\Prototype\User\Model\Form\Create(),
			'view'  => '\Snap\Prototype\User\Node\View\CreateForm',
			'viewSettings' => array( 'admin' => true )
		));
	},
	'New User' => function(){
		return new \Snap\Node\Form\Virtual(array(
			'model' => new \Snap\Prototype\User\Model\Form\Create(),
			'view'  => '\Snap\Prototype\User\Node\View\CreateForm'
		));
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