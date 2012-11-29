<?php

namespace Snap\Node\View;

class Stacked extends \Snap\Node\View {
	
	protected 
		$primaryView, 
		$secondaryView, 
		$linkVar, 
		$uniqueness,
		$listPrev, 
		$listNext, 
		$listActive, 
		$listClasses, 
		$reverse,
		$passedAttributes,
		$childStreams;
	
	protected function parseSettings( $settings = array() ){
		$this->linkVar = isset($settings['linkVar']) 
			? $settings['linkVar'] : false;
			
		$this->primaryView = isset($settings['primaryView']) 
			? $settings['primaryView'] : 'default_view';
			
		$this->secondaryView = isset($settings['secondaryView']) 
			? $settings['secondaryView'] : $this->primaryView;
		
		$this->listClasses = isset($settings['listClasses']) 
			? $settings['listClasses'] : array();
			
		$this->listPrev = isset($settings['listPrev']) 
			? $settings['listPrev'] : -1;
			
		$this->listNext = isset($settings['listNext']) 
			? $settings['listNext'] : -1;
			
		$this->listActive = isset($settings['listActive']) 
			? $settings['listActive'] : true;
			
		$this->listTotal = isset($settings['listTotal']) 
			? $settings['listTotal'] : -1;
			
		$this->reverse = isset($settings['reverse']) 
			? $settings['reverse'] : false;
			
		$this->uniqueness = isset($settings['uniqueness']) 
			? $settings['uniqueness'] : null;
		
		$this->passedAttributes = isset($settings['passedAttributes'])
			? $settings['passedAttributes'] : array();
			
		$this->childStreams = isset($settings['childStreams'])
			? $settings['childStreams'] : array();
			
		parent::parseSettings( $settings );
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'linkVar'       => 'the variable to generate links', 
			'primaryView'   => 'the stream feeding from, defaults to name of class',
			'secondaryView' => 'set the factory to use',
			'listClasses'   => 'the classes for elements based on index from active',
			'listPrev'      => 'default -1; if active is not set, -1 forced',
			'listNext'      => 'default -1; if active is not set, -1 forced',
			'listTotal'     => 'default -1; the total number of list elements to display',
			'listActive'    => 'if active is set, toggles if it should be shown',
			'reverse'       => 'reverse the order of the elements',
			'uniqueness'    => 'hashing function to show uniqueness'
		);
	}
	
	protected function _append( \Snap\Node\Snapable $in, $el ){
		return $this->reverse ? $el->prepend( $in ) : $el->append( $in );
	}
	
	public function getStreamRequest(){
		$request = array_merge( array_keys($this->childStreams),
			is_array( $this->inputStream ) ? $this->inputStream : array( $this->inputStream ) 
		);
		
		return new \Snap\Lib\Streams\Request( $request, $this );
	}
	
	protected function createList( \Snap\Lib\Mvc\Data $content ){
		$list = new \Snap\Node\Listed();
		$data = $content;
		
		if ( $data->hasVar('active') ){
			$active = $data->getVar('active');
			$i = ( $this->listPrev == -1 ? 0 : ($active - $this->listPrev) );
			$c = ( $this->listNext == -1 ? $data->count() : ($active + $this->listNext + 1) );
			
			if ( $this->listTotal != -1 && $this->listTotal < $this->listPrev ) {
				$i = $active - $this->listTotal;
			}
				
			if ( $i < 0 ){
				$i = 0;
			}
			
			if ( $this->listTotal != -1 && $i + $this->listTotal < $c ) {
				$c = $i + $this->listTotal + 1;
			}
			
			if ( $c > $data->count() ){
				$c = $data->count();
			}
		}else{
			$active = -1;
			$i = 0;
			$c = $data->count();
		}
		
		for( ; $i < $c; ++$i ){
			if ( $active == $i ){
				if ( $this->listActive ){
					$this->_append( $t = $this->createPrimaryView($data->get($i),$data->getVar('factory')), $list );
					$t->addClass('active');
				}
			}else{
				$el = $this->_append( $this->createSecondaryView($data->get($i),$data->getVar('factory')), $list );
				
				if ( $active != -1 ) {
					$pos = $i - $active;
					if ( isset($this->listClasses[$pos]) ){
						$el->addClass( $this->listClasses[$pos] );
					}
				}
			}
		}
		
		return $list;
	}
	
	protected function getTemplateVariables(){
		return array(
			'content' => $this->getStreamData()
		);
	}
	
	protected function _consume( $data = array() ){
		foreach( $this->childStreams as $incoming => $child ){
			if ( isset($data[$incoming]) ){
				$this->processStream( $child, $data[$incoming] );
			}
		}
		
		parent::_consume($data);
	}
	
	protected function createSecondaryView( $info, $factory ){
		static $count = 0;
		
		$class = $this->secondaryView;
		$el = $view = new $class( $this->getChildSettings($factory,$info) );
		
		if ( $this->linkVar && isset($info[$this->linkVar]) && $factory != null ){
			$el = $link = $factory->createLink( $info[$this->linkVar] );
			$link->append( $view );
		}
		
		return $el;
	}
	
	protected function createPrimaryView( $info, $factory ){
		static $count = 0;
		
		$class = $this->primaryView;
		$view = new $class( $this->getChildSettings($factory,$info) );
		
		return $view;
	}
	
	protected function getChildSettings( $factory, $info ){
		$settings =  array(
			'inputStream' => new \Snap\Lib\Mvc\Control( $factory, new \Snap\Lib\Mvc\Data\Instance($info) )
		);
		
		foreach( $this->childStreams as $child ){
			$var = $this->getStreamData( $child );
			
			if ( !is_null($var) ){
				if ( isset($settings[$child]) ){
					$settings[$child]->merge( $var );
				}else{
					$settings[$child] = $var;
				}
			}
		}
		
		return $this->passedAttributes + $settings;
	}
}