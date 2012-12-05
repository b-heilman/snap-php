<?php

namespace Snap\Lib\Streams;

use 
	\Snap\Node\Consumer,
	\Snap\Node\Producer,
	\Snap\Lib\Streams\WaitingQueue,
	\Snap\Lib\Streams\Request;

class Streamer {
	
	protected 
		$producers = array(), 
		$streams = array(), 
		$waiting;
	
	public function __construct(){
		$this->waiting = new WaitingQueue();
	}
	
	public function register( Producer $node ){
		if ( !$node->hasStreamer() ){
			$stream = $node->getOuputStream();
			
			$node->setStreamer($this);
			$this->waiting->addAvailable( $stream );
			
			if ( isset($this->streams[$stream]) ){
				$this->produceNode( $node );
			}else{
				if ( !isset($this->producers[$stream]) ){
					$this->producers[$stream] = array();
				}
				
				$this->producers[$stream][] = $node;
			}
		}
	}
	
	public function unregister( Producer $node ){
		$dex = array_search($node, $this->producers);
		if ( $dex !== false ){
			array_splice($this->producers, $dex, 1);
		}
	}
	
	public function consumeNode( Consumer $node ){
		if ( !$node->hasConsumed() ){
			$request = $node->getStreamRequest();
			$streams = $request->getRequestedStreams();
			
			/****************
			 * Since we request only after all the streams are registered, if a stream doesn't exist, it just doesn't exist.
			 * We can then issue the consume.
			 */
			foreach( $streams as $stream ){
				$content = $this->getContent( $stream );
				
				if ( $content != null ){
					$request->setStreamData( $stream, $content );
				}
			}
			
			if ( !$request->needsData() ){
				$this->forceConsume($node, $request);
				
				return true;
			}elseif( !$node->isWaiting() ){
				$this->addWaiting( $request );
				
				return false;
			}
		}
	}
	
	protected function forceConsume( Consumer $node, Request $request ){
		if ( !$node->hasConsumed() ){
			$node->consumeRequest( $request );
		}
	}
	
	public function produceNode( Producer $node ){
		$stream = $node->getOuputStream();
		
		if ( !$node->hasProduced() ){
			if ( $node instanceof Consumer ){
				if ( $this->consumeNode($node) ){
					$this->setStreamData( $stream, $node->produceStream() );
				}else return false;
			}else{
				$this->setStreamData( $stream, $node->produceStream() );
			}
		}
		
		
		return true;
	}
	
	public function setStreamData( $stream, $data ){
		error_log( 'settings stream : '.$stream );
		if ( !isset($this->streams[$stream]) ){
			$this->streams[$stream] = new \Snap\Lib\Mvc\Control();
		}
		
		$this->streams[$stream]->merge( $data );
		
		// send content to the waiting queue, get back and element that are now ready
		// passed in the current content for the stream
		$ready = $this->waiting->streamReady( $stream, $this->getContent($stream) );
		
		foreach( $ready as $request ){
			$master = $request->getMaster();
			
			if ( $master instanceof \Snap\Node\Producer ){
				$this->produceNode( $master );
			}else{
				$this->forceConsume( $master, $request);
			}
		}
	}
	
	protected function addWaiting( Request $request ){
		$request->getMaster()->setWaitingQueue( $this->waiting );
		$this->waiting->addRequest( $request );
	}
	
	/**
	 * Enter description here ...
	 * ----
	 * @param $stream
	 * @return \Snap\Mvc\Control
	 */
	protected function getContent( $stream ){
		if ( isset($this->streams[$stream]) ){
			if ( isset($this->producers[$stream]) ){
				$this->produceStream( $stream );
			}
			
			return $this->streams[$stream];
		}else return null;
	}
	
	protected function produceStream( $stream ){
		if ( isset($this->producers[$stream]) ){
			while( !empty($this->producers[$stream]) ){
				$this->produceNode( array_shift($this->producers[$stream]) );
			}
		}
	}
}