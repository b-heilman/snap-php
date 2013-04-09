<?php

namespace Snap\Node\View;

abstract class Listing extends \Snap\Node\Core\View {
	
	protected function parseSettings( $settings = array() ){
		if ( !isset($settings['tag']) ){
			$settings['tag'] = 'ul';
		}
		
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
	
	protected function open( $data ){
		return '<li>';
	}
	
	protected function close( $data ){
		return '</li>';
	}
	
	protected function emptyMessage(){
		return '';
	}
	
	protected function loadTemplate( $__template ){
 		$content = '';
 		$data = $this->parseStreamData( $this->getStreamData() );
 		$c = $data->count();
 		
 		if ( $c == 0 ){
 			error_log( get_class($this).' : '.$this->emptyMessage() );
 			$this->write( $this->emptyMessage(), array('tag' => 'li') );
 			$this->addClass('empty');
 		}else{
	 		for( $i = 0; $i < $c; $i++ ){
	 			$d = $this->parseListData( $data->get($i) );
	 			
	 			echo $this->open( $d );
	 			if ( is_array($d) ){
	 				$this->setTemplateData( $d );
	 				parent::loadTemplate( $__template );
	 			}else{
	 				$this->write( $d );
	 			}
	 			echo $this->close( $d );
	 		}
 		}
 	}
}