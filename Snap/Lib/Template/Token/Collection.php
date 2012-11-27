<?php

namespace Snap\Lib\Template\Token;

abstract class Collection extends \Snap\Lib\Template\Token {

	protected 
		$active;
	
	abstract protected function activate();
	
	public function canEvaluate(){
		if ( $this->active == null ){
			if ( parent::canEvaluate() ){
				$this->activate();
				
				if ( $this->active != null ){
					return $this->canEvaluate();
				}
			}
			
			return false;
		}else{
			return $this->active->canEvaluate();
		}
	}
	
	public function evaluate(){
		if ( $this->active == null ){
			if ( $this->canEvaluate() ){
				$this->activate();
				
				if ( $this->active != null ){
					return $this->evaluate();
				}
			}
			
			return parent::evaluate();
		}else{
			return $this->active;
		}
	}
	
	public function addData( $data, $value='' ){
		if ( $this->active == null ){
			parent::addData( $data, $value );
		}else{
			$this->active->addData( $data, $value );
		}
	}
}