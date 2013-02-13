<?php
$forms = array(
	'New Topic Type' => function(){
		return new \Snap\Node\Form\Virtual(array(
			'model' => new \Snap\Prototype\Topic\Model\Form\Type(),
			'view'  => '\Snap\Prototype\Topic\Node\View\TypeForm'
		));
	},
	'New Topic' => function(){
		return new \Snap\Node\Form\Virtual(array(
			'model' => new \Snap\Prototype\Topic\Model\Form\Create(),
			'view'  => '\Snap\Prototype\Topic\Node\View\CreateForm'
		));
	}
);