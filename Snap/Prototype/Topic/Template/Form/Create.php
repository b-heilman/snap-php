<?php
$this->append( $r = new \Snap\Node\Form\Row() );
		
	$r->append( new \Snap\Node\Form\Element(array(
		'input' => new \Snap\Node\Form\Input\Text(array(
			'name'  => 'new_topic_title'
		)), 
		'label' => 'Title'
	)) );
		
	if ( !$this->type ){
		$r->append( $this->select = new \Snap\Node\Form\Element(array(
			'input' => new \Snap\Node\Form\Input\Select(array( 
				'name'       => 'new_topic_type',
				'blockValue' => '',
				'options'    => \Snap\Prototype\Topic\Lib\Type::hash()
			)),
			'label' => 'Type'
		)) );
	}
	
	$r->append( $this->messaging );
	$r->append( new \Snap\Node\Form\Input\Textarea(array('name' => 'new_topic_content')) );