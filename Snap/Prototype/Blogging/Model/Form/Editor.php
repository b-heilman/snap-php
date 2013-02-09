<?php

namespace Snap\Prototype\Blogging\Model\Form;

class Editor extends Snap\Prototype\Topic\Model\Form\Create {
	
	public function __construct( $type = null ){
		if ( $type && is_string($type) ){
			$info = \Snap\Prototype\Topic\Lib\Type::get(array(
				TOPIC_TYPE_NAME => $type
			));
			
			if ( empty($type) ){
				$type = $info[TOPIC_TYPE_ID];
			}else{
				$type = \Snap\Prototype\Topic\Lib\Type::create(array(
					TOPIC_TYPE_NAME => $type
				));
			}
		}
		
		parent::__construct( $type );
		
		$this->setInputs(array(
			new \Snap\Lib\Form\Input\Formatted( 'new_topic_content', '', function( $value ){
				return "<!-- translator : template -->\n".$value;
			})
		));
	}
}