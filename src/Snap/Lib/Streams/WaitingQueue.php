<?php

namespace Snap\Lib\Streams;

class WaitingQueue {
	
	protected
		$available = array(),
		$waitingOn = array();
	
	function addAvailable( $stream ){
		if ( isset($this->available[$stream]) ){
			$this->available[$stream]++;
		}else{
			$this->available[$stream] = 1;
		}
	}
	
	function addRequest( \Snap\Lib\Streams\Request $request ){
		$requested = $request->getRequestedStreams();
		
		foreach( $requested as $stream ){
			if( !isset($this->waitingOn[$stream]) ){
				$this->waitingOn[$stream] = array();
			}
			
			$this->waitingOn[$stream][] = $request;
		}
	}
	
	function streamReady( $stream, \Snap\Lib\Mvc\Control $in ){
		$ready = array();
		
		if ( isset($this->available[$stream]) ){
			$this->available[$stream]--;
			
			if ( $this->available[$stream] == 0 ){
				$waiting = isset($this->waitingOn[$stream]) ? $this->waitingOn[$stream] : array();
				
				foreach( $waiting as $request ){
					$request->setStreamData($stream, $in);
		
					if ( !$request->needsData() ){
						$ready[] = $request;
					}
				}
			}
		}
		
		return $ready;
	}
}