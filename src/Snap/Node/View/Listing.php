<?php

namespace Snap\Node\View;

abstract class Listing extends \Snap\Node\Core\View {
	
	protected
		$childTag;
	
	public function __construct( $settings = array() ){
		if ( !isset($settings['tag']) ){
			$settings['tag'] = 'ul';
		}
		
		$this->childTag = isset($settings['childTag']) ? $settings['childTag'] : 'li';
		
		parent::__construct( $settings );
	}
	
	protected function baseClass(){
		return 'listing-view';
	}
	
	protected function parseListData( $in ){
		return $in;
	}
	
	protected function parseStreamData( \Snap\Lib\Mvc\Data $data ){
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