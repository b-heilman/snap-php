<?php

namespace Snap\Lib\File;

interface Accessor {
	public function isValid();
	public function getContent();
	public function getLink( $root );
}