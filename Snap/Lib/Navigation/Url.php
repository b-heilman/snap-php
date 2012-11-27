<?php

namespace Snap\Lib\Navigation;

class Url {

	protected 
		$navVar, 
		$base, 
		$currentValue;
	
	public function __construct( $navVar ){
		$this->navVar = $navVar;
		
		$base = $_SERVER['QUERY_STRING'];
		$pos = strpos($base, $this->navVar.'=');
		
		if ( $pos === 1 )
			$base = '';
		elseif( $pos !== false ){
			$base = substr( $base, 0, ($pos == 0)?0:($pos-1) );
		}
		
		$this->base = trim($base);
		
		$input = new \Snap\Lib\Form\Input();
		
		$this->currentValue = $input->readGet( $this->navVar );
	}
	
	public function createLink( $value, $text = '' ){
		$node = new \Snap\Node\Href( array(
			'href' => '?'.$this->base.($this->base != ''?'&':'').$this->getControlVar().'='.urlencode($value)
		) ); 
		
		if ( $text != '' ){
			$node->write( $text );
		}
		
		return $node;
	}
	
	public function getBase(){
		return $this->base;
	}
	
	public function getControlVar(){
		return $this->navVar;
	}
	
	public function getValue(){
		return $this->currentValue;
	}
}

