<?php

namespace Demo\Node\View;
// TODO : what?
class Listing extends \Snap\Node\View\Listing {
	
	protected function getTemplateHTML(){
		if ( $this->path == '' ){
			throw new \Exception( 'Path is blank for '.get_class($this) );
		}
			
		// TODO : how to avoid the collisions???
		$content = '';
		$data = $this->getStreamData();
		$c = $data->count();
			
		for( $i = 0; $i < $c; $i++ ){
			$this->setTemplateData( array('content' => $data->get($i)) );
			$t = parent::getTemplateHTML();
			$content .= "<{$this->childTag}>$t</{$this->childTag}>";
		}
			
		return $content;
	}
}