<?php

namespace Snap\Node\View;

abstract class Listing extends \Snap\Node\Core\View {
	
	protected
		$childTag;
	
	protected function parseSettings( $settings = array() ){
		if ( !isset($settings['tag']) ){
			$settings['tag'] = 'ul';
		}
		
		$this->childTag = isset($settings['childTag']) ? $settings['childTag'] : 'li';
		
		parent::parseSettings( $settings );
	}
	
	protected function baseClass(){
		return 'listing-view';
	}
	
	protected function parseListData( $in ){
		return $in;
	}
	
	// allow for late explosion of functions that were passed in
	protected function parseStreamData( \Snap\Lib\Mvc\Data $data ){
		$hasFunction = false;
		
		for( $i = 0, $c = $data->count(); $i < $c; $i++ ){
			if ( is_callable($data->get($i)) ){
				$hasFunction = true;
			}
		}
		
		if ( $hasFunction ){
			$t = new \Snap\Lib\Mvc\Data\Collection();
			for( $i = 0, $c = $data->count(); $i < $c; $i++ ){
				$v = $data->get($i);
				
				if ( is_callable($v) ){
					$t2 = $v();
					foreach( $t2 as $nt ){
						$t->add( $nt );
					}
				}else{
					$t->add( $v );
				}
			}
			
			$data = $t;
		}
		
		return $data;
	}
	
	protected function getTemplateHTML(){
 		if ( $this->path == '' ){
 			throw new \Exception( 'Path is blank for '.get_class($this) );
 		}
 		
 		// TODO : how to avoid the collisions???
 		$content = '';
 		$data = $this->parseStreamData( $this->getStreamData() );
 		$c = $data->count();
 		
 		if ( $c == 0 ){
 			$this->addClass('empty');
 		}else{
	 		for( $i = 0; $i < $c; $i++ ){
	 			$this->setTemplateData( $this->parseListData($data->get($i)) );
	 			$t = parent::getTemplateHTML();
	 			$content .= "<{$this->childTag}>$t</{$this->childTag}>";
	 		}
 		}
 		
 		return $content;
 	}
}