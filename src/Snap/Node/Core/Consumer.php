<?php

namespace Snap\Node\Core;

interface Consumer {
	
	/**
	 * Query if the consumer actually needs data, allows a consumer node to renege, and say it no longer needs data 
	 * ----
	 * 
	 */
	public function needsData();
	
	/**
	 * Pull the stream request for the consumer
	 * ----
	 * @return \Snap\Lib\Streams\Request
	 */
	public function getStreamRequest();
	
	/**
	 * Pull the stream request for the consumer, return true if the request should be removed after
	 * ----
	 * @return boolean
	 */
	public function consumeRequest( \Snap\Lib\Streams\Request $request );
	
	/**
	 * Question if the node has already consumed its request or if it's still waiting
	 * ----
	 * 
	 */
	public function hasConsumed();
	
	public function isWaiting();
	
	public function setWaitingQueue( \Snap\Lib\Streams\WaitingQueue $queue );
}