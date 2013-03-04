<?php

namespace Snap\Prototype\Topic\Node\View;

class Simple extends \Snap\Node\Core\View {

	protected function makeProcessContent(){
		/* @var \Snap\Prototype\Topic\Model\Doctrine\Topic */
		$topic = $this->getStreamData()->get(0); // assume topic
		$time = $topic->getCreationDate();
		
		return array(
			'topic'   => $topic->getName(),
			// TODO : this is not even close to right, content is now on the blog level
			'content' => $topic->getContent(),
			'date'    => $time->format('H:i:s'),
			'time'    => $time->format('H:i:s')
		);
	}
}