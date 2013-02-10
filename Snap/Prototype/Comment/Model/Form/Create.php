<?php

namespace Snap\Prototype\Comment\Model\Form;

class Create extends \Snap\Model\Form {
	public function __construct( $thread = null, $parentComment = null ){
		parent::__construct();
		
		$this->setInputs(array(
			new \Snap\Lib\Form\Input\Basic( 'thread', $thread ),
			new \Snap\Lib\Form\Input\Basic( 'parent', $parentComment ),
			new \Snap\Lib\Form\Input\Basic( 'comment', '' )
		));
	}
}