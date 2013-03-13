<?php

namespace Snap\Node\Form;

interface Input extends \Snap\Node\Core\Snapable {
	public function getType();
	public function getName();
}