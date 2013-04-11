<?php

namespace Snap\Prototype\Comment\Node\View;

class ThreadListing extends \Snap\Node\View\Listing {
	
	protected
		$commentClass;
	
	protected function parseSettings( $settings = array() ){
		$this->commentClass = isset( $settings['commentClass'] ) ? $settings['commentClass'] : 'Base';
		
		parent::parseSettings( $settings );
	}
	
	protected function getTemplatePath(){
		return null;
	}
	
	protected function parseListData( $in ){
		$class = $this->commentClass;
		
		return new $class( array( 'inputStream' => new \Snap\Lib\Mvc\Data\Collection($in)) );
	}
	
	protected function parseStreamData( \Snap\Lib\Mvc\Data $data ){
		if ( $data->count() == 1 ){
			$el = $data->get(0);
			
			if ( $el instanceof \Snap\Prototype\Comment\Model\Doctrine\Thread ){
				return new \Snap\Lib\Mvc\Data\Collection( $el->getComments() );
			}
		}
		
		return $data;
	}
}