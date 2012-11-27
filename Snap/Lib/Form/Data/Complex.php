<?php

namespace Snap\Lib\Form\Data;

class Complex extends \Snap\Lib\Form\Data\Group {
	protected $name, $currentList;

    public function __construct( $name ){
    	$this->name = $name;
    	$this->currentList = null;
    	
    	parent::__construct();
	}
    
	public function add( $in, $listName = null, $cleanName = true ){
		if ( $in instanceof \Snap\Lib\Form\Data\Group ){
			$this->currentList = $in->getName();
			$this->data[$this->currentList] = $in->getInputs();
			if ( $in->hasChanged() ){
				$this->changes[$this->currentList] = $in->getchanges();
			}
		}elseif( $in instanceof \Snap\Lib\Form\Data\Basic ){
			$name = $in->getName();
    		
    		if ( is_callable($cleanName) ){
    			$name = $cleanName($name);
    		}elseif( $cleanName ){
    			$name = substr( $name, 0, strrpos($name, '_') );
    		}
    		
			if ( $listName != null ){
				$this->currentList = $listName;
			}elseif( $this->currentList === null ){
				$this->currentList = count($this->data);
			}elseif( isset($this->data[$this->currentList][$name]) ){
				$this->currentList = count($this->data);
			}
		
			if ( !isset($this->data[$this->currentList]) ){
				$this->data[$this->currentList] = array();
			}
			
			$this->data[$this->currentList][$name] = $in;
			
			if ( $in->hasChanged() ){
				if ( !isset($this->changes[$this->currentList]) ){
					$this->changes[$this->currentList] = array();
				}
				
				$this->changes[$this->currentList][$name] = $in;
			}
		}
	}
	
	public function getInputs( $list = null ){
		if ( $list === null ){
			return parent::getInputs();
		}elseif ( isset($this->data[$list]) ) {
			return $this->data[$list];
		}else{
			return null;
		}
	}
	 
	public function getChanges( $list = null ){
		if ( $list === null ){
			return $this->changes;
		}elseif ( isset($this->changes[$list]) ) {
			return $this->changes[$list];
		}else{
			return null;
		}
	}
	
	public function getChangeList( $list = null ){
		if ( $list === null ){
			return array_keys( $this->changes );
		}elseif ( isset($this->changes[$list]) ) {
			return array_keys( $this->changes[$list] );
		}else{
			return null;
		}
	}
	
	public function getValues( $list = null ){
		return $this->pullValues( $this->data, $list );
	}
	
	public function getChangeValues( $list = null ){
		return $this->pullValues( $this->changes, $list );
	}
	
	protected function pullValues( $array, $list = null ){
		$rtn = array();
		
		if ( $list === null ){
			foreach( $array as $l => $data ){
				$tmp = array();
				foreach( $data as $name => $input ){
					$tmp[$name] = $input->getValue();
				}
				$rtn[$l] = $tmp;
			}
		}elseif ( isset($array[$list]) ) {
			foreach( $array[$list] as $name => $input ){
				$rtn[$name] = $input->getValue();
			}
		}else{
			return null;
		}
		
		return $rtn;
	}
	
	public function getName(){
		return $this->name;
	}
}