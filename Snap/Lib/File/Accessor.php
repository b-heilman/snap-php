<?php

namespace Snap\Lib\File;

interface Accessor {
	public function isValid();
	public function getContentType();
	public function getContent( \Snap\Node\Page $page ); // forward a link to the active page
	public function getLink( $root );
}