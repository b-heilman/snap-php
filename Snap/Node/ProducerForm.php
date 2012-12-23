<?php

namespace Snap\Node;

class ProducerForm extends Form
	implements Producer {
		
	protected 
		$outputStream, 
		$produced = false,
		$streamer = null,
		$factory = null;
		
	protected function parseSettings( $settings = array() ){
		$this->outputStream = isset($settings['outputStream']) 
			? $settings['outputStream'] : ( isset($settings['stream']) ? $settings['stream'] : $this->defaultStreamName() );
			
		$this->factory = isset($settings['factory']) ? $settings['factory'] : $this->defaultFactory();
		
		parent::parseSettings( $settings );
	}
	
	public function getOuputStream(){
		return $this->outputStream;
	}
	
	public function setStreamer( \Snap\Lib\Streams\Streamer $streamer ){
		$this->streamer = $streamer;
	}
	
	public function hasStreamer(){
		return !is_null($this->streamer);
	}
	
	public function hasProduced(){
		return $this->processResult !== false;
	}
	
	public function produceStream(){
		if ( $this->processResult === false ){
			$this->_process();
		}
		
		return $this->cleanOutput( new \Snap\Lib\Mvc\Data\Instance($this->processResult) );
	}
	
	/**
	 * Wraps any \Snap\Lib\Mvc\Data as \Snap\Lib\Mvc\Control, by adding a factory to it from the controller.  If mvc_factory is passed in, it passes through without
	 * any alterations
	 * 
	 * @param \Snap\Lib\Mvc\Data $output
	 */
	protected function cleanOutput( \Snap\Lib\Mvc\Data $output ){
		if ( !($output instanceof \Snap\Lib\Mvc\Control) && $this->factory != null ){
			$output = new \Snap\Lib\Mvc\Control( $this->factory, $output );
		}
		
		return $output;
	}
	
	protected function defaultFactory(){
		return new \Snap\Lib\Mvc\Control\Factory( $this );
	}
	
	protected function defaultStreamName(){
		$class = get_called_class();
		
		$pos = strrpos( $class,  '_' );
		
		return substr( $class, 0, $pos );
	}
}