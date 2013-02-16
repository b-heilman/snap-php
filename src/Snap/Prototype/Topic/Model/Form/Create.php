<?php

namespace Snap\Prototype\Topic\Model\Form;

class Create extends \Snap\Model\Form {
	
	public 
		$type;
	
	public function __construct( $type = null ){
		parent::__construct();
	
		if ( !is_null($type) && is_string($type) ){
			$this->type = \Snap\Prototype\Topic\Lib\Type::getId($type);
		}else{
			$this->type = $type;
		}
		
		$this->setInputs(array(
			new \Snap\Lib\Form\Input\Basic( 'title', '' ),
			new \Snap\Lib\Form\Input\Basic( 'content', '' )
		));
		
		if ( !$type ){
			$this->setInputs(array(
				new \Snap\Lib\Form\Input\Optionable( 'type', '',
					array('' => 'Pick A Type') + \Snap\Prototype\Topic\Lib\Type::hash()
				)
			));
				
			$this->setValidations(array(
				'new_topic_type' => new \Snap\Lib\Form\Validation\Generic( function( $val ){
					return $val !== '';
				}, 'Topic type is empty')
			));
		}
	}
}