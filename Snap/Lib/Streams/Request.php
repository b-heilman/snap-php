<?php

namespace Snap\Lib\Streams;

/******
 * Making so users can set in an array to the consumer node, the consumer node then creates this class and
 * adds a link to itself
 * -----
 * I want to allow for multiple streams to feed to one
 * array(
 *  combinedStream => array( stream1, stream2 )
 * )
 * -----
 * Organizes a consumer's request into usable data.  Object is to be taken from a consumer, so required fields
 * are known, and then passed into the consumer to set the data.
 ******/
class Request {
	
	protected 
		$streams, 
		$original, 
		$data, 
		$master;
	
	/*************
	 * Accepts as input a string, an array, or a hash.  When finished, the streams will be in the form of
	 * 'read stream' => 'consumed as stream'
	 */
	public function __construct( $streamData, \Snap\Node\Consumer $master ){
		$this->master = $master;
		$this->data = array();
		
		if ( is_string($streamData) ){
			$this->streams = array( $streamData => $streamData );
		}elseif( is_array($streamData) ){
			$this->streams = array();
			
			foreach( $streamData as $k => $stream ){
				if ( is_numeric($k) ){
					$this->streams[$stream] = $stream;
				}elseif( is_array($stream) ){
					foreach( $stream as $s ){
						$this->streams[$s] = $k;
					}
				}else{
					$this->streams[$stream] = $k;
				}
			}
		}
		
		$this->original = $this->streams;
	}
	
	/**
	 * Returns back the consumer node this request if for
	 *----
	 *@return \Snap\Node\Consumer
	 */
	public function getMaster(){
		return $this->master;
	}
	
	public function needsData(){
		return !empty($this->streams);
	}
	
	/***************
	 * Pull in the list of streams still being requested
	 */
	public function getRequestedStreams( $original = false ){
		return $original 
			? $this->original
			: ( is_array($this->streams) ?  array_keys( $this->streams ) : array() );
	}
	
	/***************
	 * Return the data of the streams that's been collected
	 * -----
	 * @returns \Snap\Mvc\Control
	 */
	public function getStreamData( $stream = null ){
		return is_null($stream) ? $this->data 
			:( isset($this->data[$stream]) ? $this->data[$stream] : null );
	}
	
	/***************
	 * Add content for a stream
	 */
	public function setStreamData( $stream, \Snap\Lib\Mvc\Control $in ){
		if ( isset($this->streams[$stream]) ){
			$s = $this->streams[$stream];
			
			if ( isset($this->data[$s]) ){
				$this->data[$s]->merge($in);
			}else{
				$this->data[$s] = $in;
			}
			
			unset( $this->streams[$stream] );
		}
	}
}