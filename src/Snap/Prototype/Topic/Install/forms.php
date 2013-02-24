<?php
$forms = array(
	'New Topic Type' => function(){
		return new \Snap\Node\Form\Virtual(array(
			'model' => new \Snap\Prototype\Topic\Model\Form\Type()
		));
	},
	'New Topic' => function(){
		return new \Snap\Node\Form\Virtual(array(
			'model' => new \Snap\Prototype\Topic\Model\Form\Create()
		));
	}
);