<?php

namespace Snap\Prototype\Comment\Control\Feed;

class TopicToComments extends \Snap\Control\Feed\Converter {
	
	protected function makeData(){
		$ctrl = $this->input;
		/** 
		 * @var \Snap\Prototype\Topic\Model\Doctrine\Topic 
		 **/
		$info = null;
		
		if ( $ctrl->hasVar('active') ){
			$info = $ctrl->get( $ctrl->getVar('active') );
		}elseif( $ctrl->count() > 0 ){
			$info = $ctrl->get( 0 );
		}
		
		if ( $info ){
			$ctrl = new \Snap\Lib\Mvc\Control( $this->factory, new \Snap\Lib\Mvc\Data\Collection($info->getThread()->getComments()) );
		}else{
			$ctrl = new \Snap\Lib\Mvc\Control( $this->factory, new \Snap\Lib\Mvc\Data\Collection(array()) );
		}
		
		return $ctrl;
	}
}