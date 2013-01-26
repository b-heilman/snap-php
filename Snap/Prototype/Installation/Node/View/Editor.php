<?php

namespace Snap\Prototype\Installation\Node\View;

class Editor extends \Snap\Node\Core\View {
	
	protected 
		$prototypeStream;
		
	protected function baseClass(){
		return 'installation-editor';
	}
	
	protected function parseSettings( $settings = array() ){
		if ( isset($settings['prototypeStream']) ){
			$input = $settings['prototypeStream'];
			
			if ( is_string($input) ){
				$this->prototypeStream = $input;
			}else{
				$this->prototypeStream = '_prototypeStream';
				$this->addData( $this->prototypeStream , $input );
			}
		}else{
			throw new \Exception( 'prototypeStream is missing: '.print_r(array_keys($settings), true) );
		}
		
		if ( isset($settings['formStream']) ){
			$settings['inputStream'] = $settings['formStream'];
		}else{
			throw new \Exception( 'formStream is missing: '.print_r(array_keys($settings), true) );
		}
		
		parent::parseSettings( $settings );
	}
	
	public function getStreamRequest(){
		$request = is_array( $this->inputStream ) ? $this->inputStream : array( $this->inputStream );
		$request[] = $this->prototypeStream;
		
		return new \Snap\Lib\Streams\Request( $request, $this );
	}
	
	protected function _consume( $data = array() ){
		$this->processStream( $this->prototypeStream, $data[$this->prototypeStream] );
		
		parent::_consume($data);
	}
	
	protected function getTemplateVariables(){
		$form      = $this->getStreamData()->get(0);

		if ( $form ){
			$prototype = new \Snap\Prototype\Installation\Lib\Prototype( $this->getStreamData($this->prototypeStream)->get(0) );
			
			return array(
				'form' => isset($prototype->forms[$form]) ? $prototype->forms[$form] : null
			);
		}else{
			return array( 'form' => null );
		}
	}
}