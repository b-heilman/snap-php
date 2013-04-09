<?php

namespace Snap\Prototype\Tagging\Node\View;

class Listing extends \Snap\Node\View\Listing {

	public function __construct( $settings = array() ){
		if ( !is_array($settings) ){
			if ( $settings instanceof \Snap\Prototype\Tagging\Lib\Taggable ){
				$settings = array( 'inputStream' => new \Snap\Lib\Mvc\Data\Collection($settings->getTags()) );
			}else throw new \Exception('I need a taggable object');
		}
		
		parent::__construct( $settings );
	}
	
	protected function emptyMessage(){
		return 'No tags yet.';
	}
	
	protected function parseListData( $in ){
		return array(
			'id'   => $in->getId(), 
			'name' => $in->getName()
		);
	}
}