<?php

namespace Snap\Prototype\Tagging\Lib;

// TODO : extend this from a interface for database interaction
interface Taggable {
	public function addTag( \Snap\Prototype\Tagging\Model\Doctrine\Tag $tag );
	public function getTags();
}