<?php

namespace Snap\Prototype\Tagging\Control\Form;

use \Estenda\Model\Doctrine as Models;

class Create extends \Snap\Control\Form {

	protected function processInput( \Snap\Lib\Form\Result $formRes ){
		$inputs = $formRes->getInputs();
		$tag = null;

		$user = \Estenda\Lib\CurrentUser::getUser();
		$taggable = $this->model->taggable;
		$tagClass = $this->model->tagClass;
		$name = $inputs['name']->getValue();

		$temp = $tagClass::find( array('name' => $name) );

		if ( !$temp ){
			$tag = new tagClass();
			$tag->setName( $name );
			$tag->persist();
				
			$taggable->addTag( $tag );

			$tag->flush();
				
			$this->model->reset();
			$formRes->addNote( "Tag Created" );
		}else{
			$tag = $temp;
			$formRes->addFormError( 'Tag already exists' );
		}

		// TODO : add the interest to your list
		return $tag;
	}
}