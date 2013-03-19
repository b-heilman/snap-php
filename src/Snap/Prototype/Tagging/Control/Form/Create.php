<?php

namespace Snap\Prototype\Tagging\Control\Form;

class Create extends \Snap\Model\Form {

	public
		$taggable,
		$tagClass;

	public function __construct( \Snap\Prototype\Tagging\Lib\Taggable $taggable = null, $tagClass = null ){
		parent::__construct();

		$this->taggable = $taggable;
		$this->tagClass = $tagClass;

		$this->setInputs(array(
				new \Snap\Lib\Form\Input\Basic( 'name', '' )
		));

		$this->setValidations(array(
				new \Snap\Lib\Form\Validation\Required( 'name', 'Need something to tag' )
		));
	}
}