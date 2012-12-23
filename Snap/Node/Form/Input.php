<?php

namespace Snap\Node\Form;

interface Input {
	public function changeName( $name );
	public function setDefaultValue( $value );
	public function setValue( $value );
	public function getValue(); // returns back the value, from the input generally
	public function getInput( \Snap\Node\Form $form ); // returns back instance of form_data_basic
	public function getName();
	public function getType();
	public function reset();
	public function hasChanged(); // TODO : couldn't this just be done by checking the input?
}