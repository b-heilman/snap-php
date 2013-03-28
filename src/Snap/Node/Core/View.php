<?php

namespace Snap\Node\Core;

// TODO : consumer probably needs to become a trait
// TODO : template probably needs to become a trait
abstract class View extends \Snap\Node\Core\Template
	implements \Snap\Node\Core\Consumer {
	
	protected 
		$data = array(),
		$consumed = false,
		$inputStream,
		$waitingQueue = null;
	
	public function __construct( $settings = array() ){
		if ( !is_array($settings) ){
			$settings = array( 'inputStream' => $settings ); 
		}
		
		parent::__construct( $settings );
	}
	
	/**
	 *  TODO : \Snap\Lib\Mvc\Data is uniform for just one stream, each element on the stack needs to be of the same class.
	 *  Ideally, this needs to be switched up so that different types from different streams can be merged into a view
	 */
	protected function parseSettings( $settings = array() ){
		if ( isset($settings['inputStream']) ){
			$input = $settings['inputStream'];
			
			if ( $input instanceof \Snap\Node\Core\Producer ){
				$this->inputStream = $input->getOuputStream();
			}elseif ( $input instanceof \Snap\Lib\Mvc\Data ){
				$this->inputStream = '_inputStream';
				$this->addData( $this->inputStream , $input );
			}else{
				$this->inputStream = $input;
			}
		}else{
			$this->inputStream = null;
		}
		
		parent::parseSettings( $settings );
	}
	
	public static function getSettings(){
		return parent::getSettings() + array(
			'inputStream' => 'stream information about what should be consumed',
			'prepop'      => '\Snap\Lib\Mvc\Data to populate the view'
		);
	}
	
	public function build(){
		// if you have your data, then run, otherwise wait for the consume process
		if ( !$this->needsData() ){
			parent::build();
		}else{
			\Snap\Node\Core\Block::build();
		}
	}
 	
	public function isWaiting(){
		return $this->waitingQueue != null;
	}
	
	public function setWaitingQueue( \Snap\Lib\Streams\WaitingQueue $queue ){
		$this->waitingQueue = $queue;
	}
	
	public function needsData(){
		return !is_null( $this->inputStream ) && empty( $this->data );
	}
	
	public function getStreamRequest(){
		return new \Snap\Lib\Streams\Request( $this->inputStream, $this );
	}
	
	public function hasConsumed(){
		return $this->consumed;
	}
	
	/**
	 * Method to be called when the data for this stream is ready to be consumed.
	 * @param $data
	 */
	public function consumeRequest( \Snap\Lib\Streams\Request $request ){
		if ( !$this->consumed ){
			$this->consumed = true;
			$this->_consume( $request->getStreamData()  );
		}
	}
	
	/**
	 * Handles the parsing of the content returned by the \Snap\Lib\Streams\Request, forwards to the processTemplate method after all data loaded
	 * @param hash $data
	 */
	protected function _consume( $data = array() ){
		if ( $this->inputStream ){
			if ( is_array($this->inputStream) ){
				foreach( $this->inputStream as $stream ){
					if ( isset($data[$stream]) ){
						$this->processStream( $stream, $data[$stream] );
					}
				}
			}elseif( isset($data[$this->inputStream]) ){
				$this->processStream( $this->inputStream, $data[$this->inputStream] );
			}
			
			if ( $this->canRunTemplate() ){
				$this->processTemplate();
			}
		}
	}
	
	protected function canRunTemplate(){
		return parent::canRunTemplate() && !$this->needsData();
	}
	
	/**
	 * This is the new handleInfo hook.  This is where you should manipulate data as it comes in.
	 * 
	 * @param string $stream
	 * @param \Snap\Lib\Mvc\Control $ctrl
	 */
	protected function processStream( $stream, \Snap\Lib\Mvc\Control $ctrl ){
		$this->addData( $stream, $ctrl );
	}
	
	public function addData( $stream, $data ){
		if ( isset($this->data[$stream]) ){
			$this->data[$stream]->merge( $data );
		}elseif( $data instanceof \Snap\Lib\Mvc\Data ){
			$this->data[$stream] = $data;
		}else{
			$this->data[$stream] = new \Snap\Lib\Mvc\Data\Collection( $data );
		}
		
		return $this;
	}
	
	/**
	 * For use by the templates for pulling out the stream data
	 * ----
	 * @param string $stream
	 * @return \Snap\Lib\Mvc\Data
	 */
	protected function getStreamData( $stream = null ) {
		$content = null;
		
		if ( is_null($stream) ){
			if ( is_array($this->inputStream) ){
				$content = new \Snap\Lib\Mvc\Data\Collection();
				
				foreach( $this->inputStream as $stream ) {
					if ( isset($this->data[$stream]) ){
						$content->merge($this->data[$stream]);
					}
				}
			}else{
				$stream = $this->inputStream;
				$content = isset( $this->data[$stream] ) ? $this->data[$stream] : null;
			}
		}else{
			$content = isset( $this->data[$stream] ) ? $this->data[$stream] : null;
		}
		
		return $content;
	}
	
	protected function baseClass(){
 		return 'view-node';
 	}
}