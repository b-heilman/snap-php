<?php

namespace Snap\Prototype\Tagging\Model\Form;

class Attach extends \Snap\Model\Form {

	public
		$taggable,
		$tagClass;

	public function __construct( \Snap\Prototype\Tagging\Lib\Taggable $taggable = null, $tagClass = null ){
		$class = explode('\\',get_class($taggable));
		$this->setUniqueTag( array_pop($class).'_'.$taggable->getId() );
		
		parent::__construct();

		$this->taggable = $taggable;
		$this->tagClass = $tagClass;

		$options = array( '' => 'Pick an Interest' );
		// TODO : bruteforce for the win
		foreach( $tagClass::all() as $tag ){
			$options[ $tag->getId() ] = $tag->getName();
		}
		
		$this->setInputs(array(
			new \Snap\Lib\Form\Input\Autocomplete( 'name', '', $options )
		));

		$this->setValidations(array(
			new \Snap\Lib\Form\Validation\Generic( 
				function( $inputs ){
					if ( !$inputs['name']->hasChanged() ){
						return array( 'name' );
					}else{
						return null;
					}
				}, 
				'Need something to tag' 
			)
		));
	}
}
