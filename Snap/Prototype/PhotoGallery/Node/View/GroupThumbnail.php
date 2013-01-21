<?php

namespace Snap\Prototype\PhotoGallery\Node\View;

class GroupThumbnail extends \Snap\Node\View 
	implements \Snap\Node\Actionable, \Snap\Node\Styleable {

	public function getStyles(){
		return array(
				new \Snap\Lib\Linking\Resource\Local( $this->page, $this )
		);
	}
	
	public function getActions(){
		return array(
				new \Snap\Lib\Linking\Resource\Local( $this->page, $this ),
				new \Snap\Lib\Linking\Resource\Local( $this->page, $this, 'jquery.carousel.js' )
		);
	}
	
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
				new \Snap\Lib\File\Accessor\Reflective( '\Snap\Prototype\PhotoGallery\Node\View\Group', array(
					'accessor' => get_class($accessor),
					'root'     => $accessor->getPath()
				))
			),
			'title' => $group->getTitle()
		);
	}
}