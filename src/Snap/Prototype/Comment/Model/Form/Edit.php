<?php

namespace Snap\Prototype\Comment\Model\Form;

use
	\Snap\Prototype\Comment\Model\Doctrine as Models;

class Edit extends \Snap\Model\Form {
	
	public
		$comment;
	
	public function __construct( Models\Comment $comment ){
		$this->comment = $comment;
		$this->setUniqueTag( $comment->getId() );
		
		parent::__construct();
		
		$this->setInputs(array(
			new \Snap\Lib\Form\Input\Basic( 'comment', $comment->getContent )
		));
		
		$this->setValidations(array(
			new \Snap\Lib\Form\Validation( 'comment', 'Delete the comment rather than make it blank' )
		));
	}
}