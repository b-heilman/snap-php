<?php

namespace Snap\Lib\Form\Input;

class Listing extends \Snap\Lib\Form\Input {
	
	protected
		$mappedClass,
		$cache = null;
	
	public function __construct( $name, $values, $mappedClass ){
		$reflection = new \ReflectionClass( $mappedClass );
		if( !$reflection->implementsInterface('Snap\Lib\Model\Inputable') ){
			// TODO : better Exceptions system exceptions
			throw new Exception('mappedClass must be of type Snap\Lib\Model\Inputable');
		}
		
		if ( isset($values[0]) && $values[0] instanceof $mappedClass ){
			$this->cache = $values;
			$values = array();
			
			foreach( $this->cache as $obj ){
				$values[] = $obj->getValue();
			}
		}
			
		$this->mappedClass = $mappedClass;
		
		parent::__construct( $name, $values );
	}
	
	public function getInstances(){
		if ( $this->cache && !$this->hasChanged() ){
			$res = is_array($this->cache) ? $this->cache : $this->cache->toArray();
		}else{
			$class = $this->mappedClass;
			$search = $this->getValue();
			
			if ( count($search) ){
				$res = $class::findAllWithValues( $this->getValue() ); // do I want to do an array_values ?
			}else{
				$res = array(); // there was nothing to search for
			}
		}
		
		usort($res, function( $a, $b ){
			return strcmp($a->getDisplay(), $b->getDisplay());
		});
		
		return $res;
	}
}