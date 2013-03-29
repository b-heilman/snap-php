<?php

namespace Snap\Lib\Form;

class Series {
	
	protected
		$name,
		$base,
		$model,
		$inputs = null,
		$control = null;
		
	public function __construct( $name, array $inputs, $count = 1 ){
		$this->name = $name;
		$this->control = new \Snap\Lib\Form\Input\Basic( $name, $count );
		
		$base = array();
		foreach( $inputs as $input ){
			$base[ $input->getName() ] = $input;
		}
		$this->base = $base;
	}
	
	public function setModel( \Snap\Model\Form $model ){
		$this->model = $model;
		$this->inputs = array();
		
		for( $i = 0, $c = $this->control->getValue(); $i < $c; $i++ ){
			$this->inputs[ $i ] = $this->makeSet( $i );
		}
	}
	
	public function makeSet( $setId ){
		$inputs = $this->base;
		
		$set = array();
		
		foreach( $inputs as $input ){
			$set[ $input->getName() ] = clone($input);
		}
		
		$this->modSeriesSet( $set, $setId );
		
		return $set;
	}
	
	public function modSeriesSet( array $set, $setId ){
		$this->model->addSeriesSet( $this, $set, $setId );
	}
	
	public function getName(){
	 	return $this->name;
	}
	
	public function getControl(){
		return $this->control;
	}
	
	public function getFields(){
		return array_keys( $this->base );
	}
	
	public function getInputs(){
		return $this->inputs;
	}
	
	public function getUniqueness(){
		return $this->control->getName();
	}
	
	public function setUnique( $unique ){
		$this->control = new \Snap\Lib\Form\Input\Basic( $unique, 1 );
	}
}