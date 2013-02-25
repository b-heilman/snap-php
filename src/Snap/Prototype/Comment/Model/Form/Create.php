<?php

namespace Snap\Prototype\Comment\Model\Form;

use
	\Snap\Prototype\Comment\Model\Doctrine as Models;
	
class Create extends \Snap\Model\Form {
	
	public
		$thread,
		$parent;
	
	public function __construct( Models\Thread $thread = null, Models\Comment $parentComment = null ){
		parent::__construct();
		
		$this->thread = $thread;
		$this->parent = $parentComment;
		
		$this->setInputs(array(
			new \Snap\Lib\Form\Input\Basic( 'comment', '' )
		));
		
		$this->setValidations(array(
			new \Snap\Lib\Form\Validation( 'comment', 'You need something for a comment' )
		));
	}
}