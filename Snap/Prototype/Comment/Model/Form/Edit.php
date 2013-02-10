<?php

namespace Snap\Prototype\Comment\Model\Form;

class Edit extends \Snap\Model\Form {
	
	public
		$comment;
	
	public function __construct( $comment = null ){
		$this->comment = $comment;
		$this->setUniqueTag( $this->comment->id() );
		
		parent::__construct();
		
		$this->setInputs(array(
			new \Snap\Lib\Form\Input\Basic( 'comment', $comment->info('comment') )
		));
	}
}