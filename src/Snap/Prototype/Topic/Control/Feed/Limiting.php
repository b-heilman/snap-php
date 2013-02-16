<?php

namespace Snap\Prototype\Topic\Control\Feed;

class Limiting extends \Snap\Control\Feed\Limiting {

	protected 
		$type;
	
	public function __construct( $settings = array() ){
		if ( !isset($settings['type']) ){
			throw new \Exception('type is required for '.get_class($this) );
		}
		
		$this->type = \Snap\Prototype\Topic\Lib\Type::getId( $settings['type'] );
		
		$settings['query'] = new \Snap\Lib\Db\Executable(
			new \Snap\Lib\Db\Query(array(
				'from'     => TOPIC_TABLE,
				'where'    => array( TOPIC_TYPE_ID => $this->type ),
			)),
			\Snap\Prototype\Topic\Lib\Element::getAdapter(),
			TOPIC_ID
		);
			
		parent::__construct( $settings );
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'type' => 'the typic type id with which to build'
		);
	}
}