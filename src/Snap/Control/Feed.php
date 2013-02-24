<?php

namespace Snap\Control;

abstract class Feed extends \Snap\Node\Core\Comment 
	implements \Snap\Node\Core\Producer {
		
	static private
		$instanceC = 0;
	
	protected 
		$outputStream, 
		$produced = false,
		$streamer = null,
		$factory = null;
	
	protected function parseSettings( $settings ){
		$this->outputStream = isset($settings['outputStream']) 
			? $settings['outputStream']
			: ( isset($settings['stream']) 
				? $settings['stream'] 
				: $this->defaultStreamName() 
			);
		
		$this->factory = isset($settings['factory']) ? $settings['factory'] : $this->defaultFactory();
		
		parent::parseSettings( $settings );
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'factory'       => 'factory to pass around, defaults to mvc_view_factory',
			'outputStream'  => 'the stream feeding to, defaults to name of class'
		);
	}
	
	public function getOuputStream(){
		return $this->outputStream;
	}

	public function setStreamer( \Snap\Lib\Streams\Streamer $streamer ){
		$this->streamer = $streamer;
	}
	
	public function hasStreamer(){
		return !is_null( $this->streamer );
	}
	
	public function hasProduced(){
		return $this->produced !== false;
	}
	
	public function produceStream(){
		if ( $this->produced === false ){
			if ( $this->comment == null ){
				$this->comment = 'output stream : '.$this->outputStream;
			}
			
			$this->produced = $this->_produce();
		}
		
		return $this->produced;
	}
	
	/**
	 * @return \Snap\Lib\Mvc\Data
	 */	
	abstract protected function makeData();
	
	private function _produce(){
		return $this->cleanOutput( $this->makeData() );	
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
		return get_class( $this ).'_'.(++self::$instanceC);
	}
}