<?php

namespace Snap\Node\Form;

interface WrappableInput extends \Snap\Node\Form\Input {
	public function setWrapper( \Snap\Node\Core\Snapable $node );
	public function getWrapper();
}