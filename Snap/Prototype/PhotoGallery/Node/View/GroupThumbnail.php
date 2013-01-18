<?php

namespace Snap\Prototype\PhotoGallery\Node\View;

class GroupThumbnail extends \Snap\Node\View {

	protected function baseClass(){
		return 'group-thumbnail';
	}

	protected function getTemplateVariables(){
		$group = $this->getStreamData()->get(0);
		$manager = $this->page->getManager();
		$accessor = $group->getAccessor();
		
		return array(
			'src'   => $manager->makeLink( $group->getIconAccessor() ),
			'link'  => $manager->makeLink(
				new \Snap\Lib\File\Accessor\Ajax( '\Snap\Prototype\PhotoGallery\Node\View\Group', array(
					'accessor' => get_class($accessor),
					'root'     => $accessor->getPath()
				))
			),
			'title' => $group->getTitle()
		);
	}
}