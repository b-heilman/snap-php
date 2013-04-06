<?php

namespace Snap\Lib\File;

interface Accessor {
	public function isValid();
	public function isRawContent();
	public function getContentType();
	public function getContent( \Snap\Node\Core\Page $page ); // forward a link to the active page
	public function getLink( $serviceRoot, $webRoot );
}