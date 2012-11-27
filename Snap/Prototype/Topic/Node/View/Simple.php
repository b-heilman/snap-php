<?php

namespace Snap\Prototype\Topic\Node\View;

class Simple extends \Snap\Node\View {

	protected function getTopic(){
		return $this->getStreamData()->get(0);
	}
	
	protected function setVariables(){
		$topic = new \Snap\Prototype\Topic\Lib\Element( $this->getTopic()  );
		list($date, $time) = explode( ' ', $topic->info('creation_date') );
		
		return array(
			'topic'   => $topic->info( TOPIC_TITLE ),
			'content' => $topic->info( 'content' ),
			'date'    => $date,
			'time'    => $time
		);
	}
}