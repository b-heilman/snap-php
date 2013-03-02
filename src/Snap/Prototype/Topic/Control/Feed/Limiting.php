<?php

namespace Snap\Prototype\Topic\Control\Feed;

class Limiting extends \Snap\Control\Feed\Limiting {

	protected 
		$type;
	
	public function __construct( $settings = array() ){
		if ( !isset($settings['type']) ){
			throw new \Exception('type is required for '.get_class($this) );
		}
		
		$qb = \Snap\Model\Doctrine::getEntityManager()->createQueryBuilder();
		//TODO : not needing to use target would be nice... very hacky
		$qb->add('select', 'target')
			->add('from', 'Snap\Prototype\Topic\Model\Doctrine\Topic target')
			->innerJoin('target.type', 'tt')
			->add('where', 'tt.name = :type')
			->setParameter('type', $settings['type']);
		
		$settings['query'] = $qb;
			
		parent::__construct( $settings );
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'type' => 'the typic type id with which to build'
		);
	}
}