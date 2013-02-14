<?php

namespace Snap\Control\Feed;

// abstract because it still needs to define makeData, which takes in a \Snap\Lib\Mvc\Control and returns a \Snap\Lib\Mvc\Control
abstract class Converter extends \Snap\Control\Feed 
	implements \Snap\Node\Core\Consumer {
		
	protected 
		$waitingQueue = null,
		$inputStream = null, 
		$input = null;
	
	protected function parseSettings( $settings = array() ){
		if ( array_key_exists('inputStream', $settings) ){
			$this->inputStream = $settings['inputStream'];
		}else{
			throw new \Exception('a converter_controller needs to know the input stream');
		}
			
		parent::parseSettings($settings);
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'inputStream'    => 'the stream from which to read',
			'outputStream'   => 'synonym for stream, just here for easier use'
		);
	}
	
	public function isWaiting(){
		return $this->waitingQueue != null;
	}
	
	public function setWaitingQueue( \Snap\Lib\Streams\WaitingQueue $queue ){
		$this->waitingQueue = $queue;
	}
	
	public function needsData(){
		return !in_null( $this->inputStream );
	}
	
	public function consumeRequest( \Snap\Lib\Streams\Request $request ){
		$in = $request->getStreamData($this->inputStream);
		
		if ( $in == null ){
			$in = new \Snap\Lib\Mvc\Control( $this->factory );
		}
		
		$this->input = $in;
	}
	
	public function getStreamRequest(){
		return new \Snap\Lib\Streams\Request( $this->inputStream, $this );
	}
	
	// abstract protected function makeData()
		
	public function hasConsumed(){
		return !is_null( $this->input );
	}
}