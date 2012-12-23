<?php

// TODO : remove this class
namespace Snap\Node;

abstract class Consuming extends \Snap\Node\Block 
	implements \Snap\Node\Consumer {
		
	protected 
		$stream = null, 
		$consumed = false,
		$waitingQueue = null;
	
	protected function parseSettings( $settings = array() ){
		if ( isset($settings['stream']) ){
			$this->stream = $settings['stream'];
		}
		
		parent::__construct( $settings );
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'stream'  => 'the stream feeding from, defaults to name of class'
		);
	}
	
	public function needsData(){
		return !is_null( $this->stream );
	}
	
	public function getStreamRequest(){
		return new \Snap\Lib\Streams\Request( $this->stream, $this );
	}
	
	public function isWaiting(){
		return $this->waitingQueue != null;
	}
	
	public function setWaitingQueue( \Snap\Lib\Streams\WaitingQueue $queue ){
		$this->waitingQueue = $queue;
	}
	
	public function hasConsumed(){
		return $this->consumed;
	}
	
	public function consumeRequest( \Snap\Lib\Streams\Request $request ){
		if ( !$this->consumed ){
			$this->consumed = true;
			$this->_consume( $request->getStreamData()  );
		}
	}
	
	abstract protected function _consume( $input );
}