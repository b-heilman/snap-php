<?php

namespace Snap\Prototype\PhotoGallery\Node\View;

class GalleryThumbnail extends \Snap\Node\View {

	protected function baseClass(){
		return 'gallery-thumbnail';
	}

	protected function getTemplateVariables(){
		$group = $this->getStreamData()->get(0);
		
		return array(
			'src'   => $this->page->makeLibraryLink( $group->getIcon() ),
			'title' => $group->getTitle()
		);
	}
}