<?php

namespace Snap\Prototype\Tagging\Control\Form;

class Attach extends \Snap\Control\Form {

	protected function processInput( \Snap\Lib\Form\Result $formRes ){
		$inputs = $formRes->getInputs();
		$tag = null;

		$user = \Estenda\Lib\CurrentUser::getUser();
		$taggable = $this->model->taggable;
		$tagClass = $this->model->tagClass;
		$name = trim( $inputs['name_text']->getValue() );

		$temp = $tagClass::find( array('name' => $name) );

		if ( !$temp ){
			$tag = new $tagClass();
			$tag->setName( $name );
			$tag->addTarget( $taggable );
			$tag->persist();

			$tag->flush();

			$this->model->reset();
			$formRes->addNote( "Tag created and added" );
		}else{
			$tag = $temp;
			$tag->addTarget( $taggable );
			
			$this->model->reset();
			
			$formRes->addFormError( 'Tag added' );
		}

		// TODO : add the interest to your list
		return $tag;
	}
}