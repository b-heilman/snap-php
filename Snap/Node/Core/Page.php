<?php

namespace Snap\Node\Core;

interface Page {
	public function setTitle( $title );
	public function setPageData( $data );
	
	public function makeResourceLink( $resource );
	public function makeLibraryLink( $library );
	public function makeAjaxLink( $class, $data );
}