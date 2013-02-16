<?php

namespace Snap\Node\Core;

interface Producer {

	public function getOuputStream();
	// set a position for the producer.  This is to be user by a device controlling the production
	public function setStreamer( \Snap\Lib\Streams\Streamer $streamer );
	public function hasStreamer();
	public function hasProduced();
	
	public function produceStream();
}