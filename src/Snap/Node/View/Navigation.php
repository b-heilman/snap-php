<?php

namespace Snap\Node\View;

class Navigation extends \Snap\Node\Core\View {

	protected 
		$navStream;
		
	protected function parseSettings( $settings = array() ){
		if ( isset($settings['navStream']) ){
			$input = $settings['navStream'];
			
			if ( is_string($input) ){
				$this->navStream = $input;
			}else{
				$this->navStream = '_navStream';
				$this->addData( $this->navStream , $input );
			}
		}else{
			throw new \Exception( 'navStream is missing: '.print_r(array_keys($settings), true) );
		}
		
		parent::parseSettings( $settings );
	}
	
	public function getStreamRequest(){
		$request = is_array( $this->inputStream ) ? $this->inputStream : array( $this->inputStream );
		$request[] = $this->navStream;
		
		return new \Snap\Lib\Streams\Request( $request, $this );
	}
	
	protected function _consume( $data = array() ){
		$this->processStream( $this->navStream, $data[$this->navStream] );
		
		parent::_consume($data);
	}
	
	protected function makeProcessContent(){
		if ( is_null($this->getStreamData($this->navStream)) ){
			throw new \Exception('yay');
		}
		
		return array(
			'factory'   => $this->getStreamData( $this->navStream )->getVar( 'factory' ),
			'active'    => $this->getStreamData( $this->navStream )->get( 0 )
		);
	}
}