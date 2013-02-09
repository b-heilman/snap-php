<?php

namespace Snap\Prototype\Topic\Model\Form;

class Create extends \Snap\Model\Form {
	
	protected 
		$type;
	
	public function __construct( $type = null ){
		parent::__construct();
	
		$this->type = $type;
		
		$this->setInputs(array(
			new \Snap\Lib\Form\Input\Basic( 'new_topic_title', '' ),
			new \Snap\Lib\Form\Input\Basic( 'new_topic_content', '' )
		));
		
		if ( $type ) {
			$this->setInputs(array(
				new \Snap\Lib\Form\Input\Optionable( 'new_topic_type', '',
					array('' => 'Pick A Type') + \Snap\Prototype\Topic\Lib\Type::hash()
				)
			));
			
			$this->setValidations(array(
				'new_topic_type' => new \Snap\Lib\Form\Validation\Generic( function( $val ){
					return $val !== '';
				}, 'Topic type is empty')
			));
		}else{
			$this->setInputs(array(
				new \Snap\Lib\Form\Input\Stub( 'new_topic_type', $type)
			));
		}
	}
}