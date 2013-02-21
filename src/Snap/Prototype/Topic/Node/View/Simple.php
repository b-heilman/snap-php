<?php

namespace Snap\Prototype\Topic\Node\View;

class Simple extends \Snap\Node\Core\View {

	protected function makeProcessContent(){
		$topic = new \Snap\Prototype\Topic\Lib\Element( $this->getStreamData()->get(0)  );
		$timestamp = $topic->info('creation_date');
		
		if( $timestamp ){
			list($date, $time) = explode( ' ', $timestamp );
		}else{
			$date = 0;
			$time = 0;
		}
		
		return array(
			'topic'   => $topic->info( TOPIC_TITLE ),
			'content' => $topic->info( 'content' ),
			'date'    => $date,
			'time'    => $time
		);
	}
}