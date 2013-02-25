<?php

namespace Snap\Prototype\Comment\Model\Form;

use
	\Snap\Prototype\Comment\Model\Doctrine as Models;

class Delete extends \Snap\Model\Form {
	
	public
		$comment;
	
	public function __construct( Models\Comment $comment ){
		$this->comment = $comment;
		$this->setUniqueTag( $this->comment->id() );
		
		parent::__construct();
		
		$this->setInputs(array(
			new \Snap\Lib\Form\Input\Checkbox( 'remove', 1 )
		));
	}
}