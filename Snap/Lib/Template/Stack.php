<?php

namespace Snap\Lib\Template;

class Stack extends \Snap\Lib\Core\Stack {

	protected 
		$data;
	
	public function __construct( array $data = array() ){
		parent::__construct();
		
		$this->data = $data;
	}
	
	public function addData( $data, $value='' ){
		if ( is_array($data) ) {
			$this->data += $data;
		}else{
			$this->data[$data] = $value;
		}
		
		$c = count($this->content);
	}
	
	public function _add( \Snap\Lib\Core\Token $in, $place ){
		if ( $in instanceof \Snap\Lib\Template\Token ){
			$in->addData( $this->data );
		
			if ( $in->canEvaluate() ){
				$this->simplify( $place );
			}else{
				throw new \Exception( 
					'Data not available but needed: ' . print_r( $in->getRequirements(), true )
					. "\nAvailable: " . print_r( $this->data, true )
					. "\nFor: " . get_class( $in )
					. "\nContent: " . $in->getContent()
				);
			}
		}
		
		parent::_add( $in, $place );
	}
	
	protected function simplify( $where ){
		$el = $this->content[$where];
		$change = 0;
		
		if ( $el instanceof \Snap\Lib\Template\Token && $el->canEvaluate() ){
			$eval = $el->evaluate();

			if ( is_string($eval) ){
				$last = count($this->content) - 1;
				
				$this->content[$where] = $eval;
				
				$change = $this->merge($where);
			}elseif( $eval instanceof \Snap\Lib\Core\Stack ){
				array_splice( $this->content, $where, 1, $eval->content );
				
				$this->merge( $where );
				$this->merge( $where + count($eval) - 1 );
				
				$change = 2;
			}else{
				$this->content[$where] = $eval;
			}
		}
		
		return $change;
	}
	
	protected function merge( $where ){
		$changes = 0;
		
		if ( isset($this->content[$where]) ){
			$last = count($this->content) - 1;
			
			if ( is_string($this->content[$where]) ){
				if ( $where < $last && is_string($this->content[$where + 1]) ){
					$this->content[$where] .= $this->content[$where + 1];
					array_splice($this->content, $where+1, 1);
					
					$changes |= 1;
				}
				
				if ( $where > 0 && is_string($this->content[$where - 1]) ) {
					$this->content[$where - 1] .= $this->content[$where];
					array_splice($this->content, $where, 1);
					
					$changes |= 2;
				}
			}
		}
		
		return $changes;
	}
	
	public function canEvaluate(){
		$valid = true;
		$c = count($this->content);
		
		for ( $i = 0; $valid && $i < $c; $i++ ){ 
			$el = $this->content[$i];
			if ( $el instanceof \Snap\Lib\Template\Token ) {
				$valid = $el->canEvaluate(); 
			}
		}
		
		return $valid;
	}
}