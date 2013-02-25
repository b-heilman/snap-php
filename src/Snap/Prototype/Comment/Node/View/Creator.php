<?php

namespace Snap\Prototype\Comment\Node\View;

// TODO : abstaract this kind of logic out to be a callback function?
//      : how to make this more flexible?

class Creator extends \Snap\Node\Core\View {
	
	protected function makeProcessContent(){
		$model = new \Snap\Prototype\Comment\Model\Form\Create(
			$this->getStreamData()->getPrimary()
		);
		$view = new \Snap\Prototype\Comment\Node\Form\Create( array('model' => $model) );
		$control = new \Snap\Prototype\Comment\Control\Form\Create( array('model' => $model) );
		
		return array(
			'view'    => $view, 
			'control' => $control
		);
	}
}