<?php

namespace Snap\Prototype\Comment\Node\View;

// TODO : abstaract this kind of logic out to be a callback function?
//      : how to make this more flexible?

class Creator extends \Snap\Node\Core\View {
	
	protected function makeProcessContent(){
		$info = $this->getStreamData()->getPrimary();
		
		$model = new \Snap\Prototype\Comment\Model\Form\Create( $info[TOPIC_COMMENT_THREAD] );
		$view = new \Snap\Prototype\Comment\Node\View\CreateForm( array('model' => $model) );
		$control = new \Snap\Prototype\Comment\Control\Feed\CreateForm( array('model' => $model) );
		
		return array(
			'view'    => $view, 
			'control' => $control
		);
	}
}