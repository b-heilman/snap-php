<?php

namespace Snap\Prototype\Comment\Node\View;

class ThreadListing extends \Snap\Node\View\Listing {
	
	protected function getTemplatePath(){
		return $this->getTemplate('View/Base.php');
	}
	
	protected function parseListData( $in ){
		return array(
			'comment' => new Base(array(
				'inputStream' => new \Snap\Lib\Mvc\Data\Collection( $in )
			))
		);
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